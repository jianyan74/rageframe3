<?php

namespace common\forms;

use Yii;
use yii\base\Model;
use yii\helpers\Json;
use yii\web\UnprocessableEntityHttpException;
use common\enums\PayTypeEnum;
use common\models\extend\PayLog;
use common\interfaces\PayHandler;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;
use common\enums\PayTradeTypeEnum;

/**
 * 支付校验
 *
 * Class PayForm
 * @package common\forms
 * @author jianyan74 <751393839@qq.com>
 */
class PayForm extends PayLog
{
    public $data;

    /**
     * 授权码
     *
     * @var
     */
    public $code;

    /**
     * @var
     */
    private $_handlers;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['order_group', 'pay_type', 'data', 'trade_type', 'member_id'], 'required'],
            [['pay_type'], 'in', 'range' => PayTypeEnum::getKeys()],
            [['notify_url', 'return_url', 'code', 'auth_code', 'openid'], 'string'],
            [['data'], 'safe'],
            [['trade_type'], 'verifyTradeType'],
        ];
    }

    /**
     * 校验交易类型
     *
     * @param $attribute
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function verifyTradeType($attribute)
    {
        try {
            $this->data = Json::decode($this->data);
        } catch (\Exception $e) {
            $this->addError($attribute, $e->getMessage());

            return;
        }

        switch ($this->pay_type) {
            case PayTypeEnum::WECHAT :
                if (!in_array($this->trade_type, array_keys(PayTradeTypeEnum::getWechatMap()))) {
                    $this->addError($attribute, '微信交易类型不符');

                    return;
                }

                // 直接通过授权码进行支付
                if ($this->code) {
                    if ($this->trade_type == PayTradeTypeEnum::WECHAT_MINI) {
                        $auth = Yii::$app->wechat->miniProgram->auth->session($this->code);
                        Yii::$app->services->base->getWechatError($auth);
                        $this->openid = $auth['openid'];
                    }

                    if ($this->trade_type == PayTradeTypeEnum::WECHAT_MP) {
                        $user = Yii::$app->wechat->app->oauth->userFromCode($this->code);
                        $this->openid = $user->getId();
                    }
                }

                if ($this->trade_type == PayTradeTypeEnum::WECHAT_POS && !$this->auth_code) {
                    $this->addError($attribute, '找不到付款码');
                }

                break;
            case PayTypeEnum::ALI :
                if (!in_array($this->trade_type, array_keys(PayTradeTypeEnum::getAliMap()))) {
                    $this->addError($attribute, '支付宝交易类型不符');
                }

                // 面对面收款
                if ($this->trade_type == PayTradeTypeEnum::ALI_POS && !$this->auth_code) {
                    $this->addError($attribute, '找不到付款码');
                }

                break;
            case PayTypeEnum::UNION :
                if (!in_array($this->trade_type, array_keys(PayTradeTypeEnum::getUnionMap()))) {
                    $this->addError($attribute, '银联交易类型不符');
                }
                break;
            // 海外信用卡 stripe
            case PayTypeEnum::STRIPE :
                if (!in_array($this->trade_type, ['cards', 'card'])) {
                    $this->addError($attribute, 'Strip交易类型不符');
                }
                break;
        }
    }

    /**
     * 执行类
     *
     * @param array $handlers
     */
    public function setHandlers(array $handlers)
    {
        $this->_handlers = $handlers;
    }

    /**
     * @return array|\EasyWeChat\Kernel\Support\Collection|mixed|object|\Psr\Http\Message\ResponseInterface|string
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
     */
    public function getConfig()
    {
        if (!isset($this->_handlers[$this->order_group])) {
            throw new UnprocessableEntityHttpException('找不到订单组别');
        }

        /** @var Model|PayHandler $model */
        $model = new $this->_handlers[$this->order_group]();
        if (!($model instanceof PayHandler)) {
            throw new UnprocessableEntityHttpException('无效的订单组别');
        }

        $model->attributes = $this->data;
        if (!$model->validate()) {
            throw new UnprocessableEntityHttpException(Yii::$app->services->base->analysisErr($model->getFirstErrors()));
        }

        // 系统内支付
        if (in_array($this->pay_type, [PayTypeEnum::USER_MONEY, PayTypeEnum::PAY_ON_DELIVERY])) {
            return [];
        }

        $log = new PayLog();
        if ($model->isQueryOrderSn() == true && ($history = Yii::$app->services->extendPay->findByOrderSn($model->getOrderSn()))) {
            $log = $history;
        }

        $log->out_trade_no = $model->getOutTradeNo();
        if (empty($log->out_trade_no)) {
            $log->out_trade_no = date('YmdHis') . StringHelper::random(8, true);
        }

        $log->attributes = ArrayHelper::toArray($this);
        $log->order_sn = $model->getOrderSn();
        $log->body = $model->getBody() . '-' . $log->order_sn;
        $log->detail = $model->getDetails();
        $log->merchant_id = $model->getMerchantId();
        $log->total_fee = $model->getTotalFee();
        $log->pay_fee = $log->total_fee;
        if ($log->total_fee <= 0) {
            throw new UnprocessableEntityHttpException('请使用余额支付');
        }

        if (!$log->save()) {
            throw new UnprocessableEntityHttpException(Yii::$app->services->base->analysisErr($log->getFirstErrors()));
        }

        return $this->payConfig($log);
    }

    /**
     * @return array|\EasyWeChat\Kernel\Support\Collection|mixed|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
     */
    protected function payConfig(PayLog $log)
    {
        switch ($log->pay_type) {
            case PayTypeEnum::WECHAT :
                return Yii::$app->services->extendPay->wechat($log);
                break;
            case PayTypeEnum::ALI :
                return Yii::$app->services->extendPay->alipay($log);
                break;
            case PayTypeEnum::UNION :
                return Yii::$app->services->extendPay->unipay($log);
                break;
            case PayTypeEnum::BYTE_DANCE :
                return Yii::$app->services->extendPay->byteDance($log);
                break;
            case PayTypeEnum::STRIPE :
                return Yii::$app->services->extendPay->stripe($log);
                break;
        }
    }
}
