<?php

namespace services\member;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use common\components\Service;
use common\enums\StatusEnum;
use common\enums\TransferTypeEnum;
use common\enums\WithdrawTransferStatusEnum;
use common\helpers\ArrayHelper;
use common\models\member\WithdrawDeposit;

/**
 * Class WithdrawDepositService
 * @package services\member
 */
class WithdrawDepositService extends Service
{
    /**
     * 转账
     *
     * @param WithdrawDeposit $model
     */
    public function transfer(WithdrawDeposit $model)
    {
        $model->transfer_name = TransferTypeEnum::getValue($model->transfer_type);
        $model->transfer_time = time();

        switch ($model->transfer_type) {
            // 微信转账到微信零钱
            case TransferTypeEnum::WECHAT_BALANCE :
                return $this->wechatToBalance($model);
                break;
            // 微信转账到银行卡
            case TransferTypeEnum::WECHAT_BANK_CARD :
                return $this->wechatToBankCard($model);
                break;
            // 支付宝转账到支付宝账号
            case TransferTypeEnum::ALI_BALANCE :
                return $this->alipayToAccount($model);
                break;
        }
    }

    /**
     * 提现到零钱
     *
     * @param WithdrawDeposit $model
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function wechatToBalance(WithdrawDeposit $model)
    {
        $result = Yii::$app->wechat->payment->transfer->toBalance([
            'partner_trade_no' => $model->withdraw_no, // 商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
            'openid' => $model->account_number,
            'check_name' => 'FORCE_CHECK', // NO_CHECK：不校验真实姓名, FORCE_CHECK：强校验真实姓名
            're_user_name' => $model->realname, // 如果 check_name 设置为FORCE_CHECK，则必填用户真实姓名
            'amount' => $model->cash * 100, // 企业付款金额，单位为分
            'desc' => $model->memo, // 企业付款操作说明信息。必填
        ]);

        if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
            throw new UnprocessableEntityHttpException($result['err_code_des']);
        }

        $model->transfer_no = $result['payment_no'];
        $model->transfer_account_no = $result['mch_id'];
        $model->save();

        return $result;
    }

    /**
     * 小程序提现
     *
     * @param WithdrawDeposit $model
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function wechatMiniProgramToBalance(WithdrawDeposit $model)
    {
        // 设置appid
        Yii::$app->params['wechatPaymentConfig'] = ArrayHelper::merge(Yii::$app->params['wechatPaymentConfig'], [
            'app_id' => Yii::$app->services->config->backendConfig('miniprogram_appid'),
        ]);

        return $this->wechatToBalance($model);
    }

    /**
     * 提现到银行卡
     *
     * @param WithdrawDeposit $model
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function wechatToBankCard(WithdrawDeposit $model)
    {
        $bankNumber = Yii::$app->services->bankNumber->findByBankName($model->bank_name);
        if (!$bankNumber) {
            throw new UnprocessableEntityHttpException('该银行暂时不支持提现');
        }

        $result = Yii::$app->wechat->payment->transfer->toBankCard([
            'partner_trade_no' => $model->withdraw_no,
            'enc_bank_no' => $model->account_number, // 银行卡号
            'enc_true_name' => $model->realname,   // 银行卡对应的用户真实姓名
            'bank_code' => $bankNumber->bank_number, // 银行编号
            'amount' => $model->cash * 100, // 企业付款金额，单位为分
            'desc' => $model->memo, // 企业付款操作说明信息。必填
        ]);

        if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
            throw new UnprocessableEntityHttpException($result['err_code_des']);
        }

        // 记录日志
        $model->transfer_no = $result['payment_no'];
        $model->transfer_account_no = $result['mch_id'];
        $model->save();

        return $result;
    }

    /**
     * 支付宝单次转账
     *
     * @param WithdrawDeposit $model
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws \yii\base\InvalidConfigException
     */
    public function alipayToAccount(WithdrawDeposit $model)
    {
        $result = Yii::$app->pay->alipay->transfer([
            'out_biz_no' => $model->withdraw_no,
            'payee_account' => $model->account_number,
            'amount' => $model->cash,
            'payee_real_name' => $model->realname, // 非必填
            'remark' => $model->memo, // 非必填
        ]);

        if ($result['code'] != '10000') {
            if (isset($result['sub_msg'])) {
                throw new UnprocessableEntityHttpException($result['sub_msg']);
            }

            throw new UnprocessableEntityHttpException($result['msg']);
        }

        // 记录日志
        $model->transfer_no = $result['order_id'];
        $model->save();

        return $result;
    }

    /**
     * @var string[]
     */
    protected $wechatStatus = [
        'SUCCESS' => '转账成功',
        'FAILED' => '转账失败',
        'PROCESSING' => '处理中',
    ];

    /**
     * @param $withdraw_no
     * @param bool $allReturn
     * @return mixed
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function queryByWithdrawNo($withdraw_no, $allReturn = false)
    {
        $log = $this->findByWithdrawNo($withdraw_no);
        if (!$log) {
            throw new NotFoundHttpException('找不到转账记录');
        }

        switch ($log->transfer_type) {
            case TransferTypeEnum::ALI_BANK_CARD :
            case TransferTypeEnum::ALI_BALANCE :
                $result = Yii::$app->pay->alipay->transferQuery([
                    'out_biz_no' => $withdraw_no,
                    'order_id' => $log->transaction_id,
                ]);

                if ($result['code'] != '10000') {
                    if (isset($result['sub_msg'])) {
                        throw new UnprocessableEntityHttpException($result['sub_msg']);
                    }

                    throw new UnprocessableEntityHttpException($result['msg']);
                }

                return $allReturn ? $result : $result['msg'];

                break;
            case TransferTypeEnum::WECHAT_BANK_CARD :
                $result = Yii::$app->wechat->payment->transfer->queryBankCardOrder($withdraw_no);
                if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
                    throw new UnprocessableEntityHttpException($result['reason']);
                }
                return $this->wechatStatus[$result['status']];
                break;
            case TransferTypeEnum::WECHAT_BALANCE :
                $result = Yii::$app->wechat->payment->transfer->queryBalanceOrder($withdraw_no);
                if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
                    throw new UnprocessableEntityHttpException($result['reason']);
                }

                return $this->wechatStatus[$result['status']];
                break;
        }

        throw new UnprocessableEntityHttpException('无效的记录');
    }

    /**
     * @return int|string
     */
    public function getWithdrawalSuccessSum($addon_name = '', $member_id = '')
    {
        return WithdrawDeposit::find()
                ->where(['status' => StatusEnum::ENABLED])
                ->andWhere(['is_addon' => !empty($addon_name) ? StatusEnum::ENABLED : StatusEnum::DISABLED])
                ->andWhere(['transfer_status' => WithdrawTransferStatusEnum::TRANSFER_SUCCESS])
                ->andFilterWhere(['addon_name' => $addon_name])
                ->andFilterWhere(['member_id' => $member_id])
                ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                ->sum('cash') ?? 0;
    }

    /**
     * @return int
     */
    public function getWithdrawalCashApplySum($addon_name = '', $member_id = '')
    {
        return WithdrawDeposit::find()
                ->where(['status' => StatusEnum::DISABLED])
                ->andWhere(['is_addon' => !empty($addon_name) ? StatusEnum::ENABLED : StatusEnum::DISABLED])
                ->andWhere(['transfer_status' => WithdrawTransferStatusEnum::TRANSFER_SUCCESS])
                ->andFilterWhere(['addon_name' => $addon_name])
                ->andFilterWhere(['member_id' => $member_id])
                ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                ->sum('cash') ?? 0;
    }

    /**
     * @param $withdraw_no
     * @return array|\yii\db\ActiveRecord|null|WithdrawDeposit
     */
    public function findByWithdrawNo($withdraw_no)
    {
        return WithdrawDeposit::find()
            ->where(['withdraw_no' => $withdraw_no])
            ->one();
    }

    /**
     * @return int|string
     */
    public function getApplyCount($merchant_id = '')
    {
        return WithdrawDeposit::find()
            ->select('id')
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['transfer_status' => WithdrawTransferStatusEnum::APPLY])
            ->andFilterWhere(['id' => $merchant_id])
            ->count() ?? 0;
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null|WithdrawDeposit
     */
    public function findById($id)
    {
        return WithdrawDeposit::find()
            ->where(['id' => $id])
            ->one();
    }
}
