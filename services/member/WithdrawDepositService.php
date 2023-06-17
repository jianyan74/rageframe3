<?php

namespace services\member;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use common\components\Service;
use common\enums\StatusEnum;
use common\enums\TransferTypeEnum;
use common\enums\WithdrawTransferStatusEnum;
use common\helpers\ArrayHelper;
use common\models\member\WithdrawDeposit;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use EasyWeChat\Kernel\Support\Collection;
use GuzzleHttp\Exception\GuzzleException;
use Omnipay\Common\Exception\InvalidRequestException;
use Psr\Http\Message\ResponseInterface;

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
     * @throws InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws GuzzleException
     */
    public function wechatToBalance(WithdrawDeposit $model)
    {
        $params = [
            'out_batch_no' => $model->batch_no, // 商户系统内部的商家批次单号，要求此参数只能由数字、大小写字母组成，在商户系统内部唯一
            'batch_name' => '批量转账 - ' . $model->realname, // 转账标题
            'batch_remark' => '批量转账 - ' . $model->realname, // 转账备注
            'total_amount' => $model->cash * 100, // 转账总金额
            'total_num' => 1, // 转账总金额
            'transfer_detail_list' => [
                [
                    'out_detail_no' => $model->withdraw_no, // 商户订单号
                    'transfer_amount' => $model->cash * 100, // 企业付款金额，单位为分
                    'transfer_remark' => !empty($model->memo) ? $model->memo : '无', // 企业付款操作说明信息。必填
                    'openid' => $model->account_number,
                    'user_name' => $model->realname, // 明细转账金额 >= 2,000，收款用户姓名必填
                ],
            ],
        ];

        $result = Yii::$app->pay->wechat->transfer($params);

        $model->transfer_no = $result['payment_no'];
        $model->transfer_account_no = $result['mch_id'];
        $model->save();

        return $result;
    }

    /**
     * 小程序提现
     *
     * @param WithdrawDeposit $model
     * @return array|Collection|object|ResponseInterface|string
     * @throws UnprocessableEntityHttpException
     * @throws InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws GuzzleException
     */
    public function wechatMiniProgramToBalance(WithdrawDeposit $model)
    {
        // 设置appid
        Yii::$app->params['wechatPaymentConfig'] = ArrayHelper::merge(Yii::$app->params['wechatPaymentConfig'], [
            'app_id' => Yii::$app->services->config->backendConfig('wechat_mini_app_id'),
        ]);

        return $this->wechatToBalance($model);
    }

    /**
     * 提现到银行卡
     *
     * @param WithdrawDeposit $model
     * @throws UnprocessableEntityHttpException
     * @throws InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws RuntimeException
     * @throws GuzzleException
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
     * @throws InvalidRequestException
     * @throws InvalidConfigException
     */
    public function alipayToAccount(WithdrawDeposit $model)
    {
        $result = Yii::$app->pay->alipay->transfer([
            'out_biz_no' => $model->withdraw_no,
            'trans_amount' => $model->cash,
            'payee_info' => [
                'identity' => $model->account_number, // 提现账号
                'name' => $model->realname, // 提现金额
            ],
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
     * @throws InvalidRequestException
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
                $result = Yii::$app->pay->alipay->find([
                    'out_biz_no' => $withdraw_no,
                    '_type' => 'transfer',
                ]);

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
     * @return array|ActiveRecord|null|WithdrawDeposit
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
     * @return array|ActiveRecord|null|WithdrawDeposit
     */
    public function findById($id)
    {
        return WithdrawDeposit::find()
            ->where(['id' => $id])
            ->one();
    }
}
