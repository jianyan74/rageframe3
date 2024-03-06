<?php

namespace services\extend;

use Yansongda\Pay\Exception\InvalidResponseException;
use Yansongda\Pay\Pay;
use Yii;
use yii\web\UnprocessableEntityHttpException;
use common\enums\PayTypeEnum;
use common\enums\StatusEnum;
use common\enums\PayTradeTypeEnum;
use common\helpers\BcHelper;
use common\helpers\StringHelper;
use common\models\extend\PayLog;
use common\models\extend\PayRefund;
use common\forms\CreditsLogForm;
use common\forms\PayForm;

/**
 * Class PayService
 * @package services\extend
 * @author jianyan74 <751393839@qq.com>
 */
class PayService
{
    /**
     * 微信支付
     *
     * @param PayForm $payForm
     * @param $baseOrder
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function wechat(PayLog $payLog)
    {
        $order = [
            'out_trade_no' => $payLog->out_trade_no, // 订单号
            'description' => $payLog->body, // 内容
            'amount' => [
                'total' => (int)($payLog->total_fee * 100),
            ],
            'notify_url' => $payLog->notify_url, // 通知地址
        ];

        //  判断如果是公众号/小程序支付
        if (in_array($payLog->trade_type, [PayTradeTypeEnum::WECHAT_MP, PayTradeTypeEnum::WECHAT_MINI])) {
            $order['payer'] = [
                'openid' => $payLog->openid
            ];
        }

        //  判断如果是手机H5支付
        if (in_array($payLog->trade_type, [PayTradeTypeEnum::WECHAT_WAP])) {
            $order['scene_info'] = [
                'payer_client_ip' => Yii::$app->request->userIP,
                'h5_info' => [
                    'type' => 'Wap', // iOS, Android, Wap
                    // 'bundle_id' => '', // iOS平台BundleID
                    // 'package_name' => '', // Android平台PackageName
                ],
            ];
        }

        //  判断如果是刷卡支付(V3暂时不支持)
        if ($payLog->trade_type == PayTradeTypeEnum::WECHAT_POS) {
            $payLog->auth_code = StringHelper::replace('\r', '', $payLog->auth_code);
            $payLog->auth_code = StringHelper::replace('\n', '', $payLog->auth_code);
            $order['auth_code'] = (int)$payLog->auth_code;
        }

        // 交易类型
        $tradeType = $payLog->trade_type;

        return Yii::$app->pay->wechat->$tradeType($order);
    }

    /**
     * 支付宝支付
     *
     * @param PayLog $payLog
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function alipay(PayLog $payLog)
    {
        // 配置
        $config = [
            'notify_url' => $payLog->notify_url, // 支付通知回调地址
        ];
        // 买家付款成功跳转地址
        !empty($payLog->return_url) && $config['return_url'] = $payLog->return_url;

        // 生成订单
        $order = [
            'out_trade_no' => $payLog->out_trade_no,
            'total_amount' => $payLog->total_fee,
            'subject' => $payLog->body,
        ];

        // 面对面收款
        if ($payLog->trade_type == PayTradeTypeEnum::ALI_POS) {
            $payLog->auth_code = StringHelper::replace('\r', '', $payLog->auth_code);
            $payLog->auth_code = StringHelper::replace('\n', '', $payLog->auth_code);
            $order['auth_code'] = (int)$payLog->auth_code;
        }

        // 交易类型
        $tradeType = $payLog->trade_type;

        return [
            'config' => Yii::$app->pay->alipay($config)->$tradeType($order),
        ];
    }

    /**
     * 银联支付
     *
     * @param PayForm $payForm
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function unipay(PayLog $payLog)
    {
        // 配置
        $config = [
            'notify_url' => $payLog->notify_url, // 支付通知回调地址
            'return_url' => $payLog->return_url, // 买家付款成功跳转地址
        ];

        // 生成订单
        $order = [
            'orderId' => $payLog->out_trade_no, //Your order ID
            'txnTime' => date('YmdHis'), //Should be format 'YmdHis'
            'orderDesc' => $payLog->body, //Order Title
            'txnAmt' => $payLog->total_fee, //Order Total Fee
        ];

        // 交易类型
        $tradeType = $payLog->trade_type;

        return Yii::$app->pay->unipay($config)->$tradeType($order);
    }

    /**
     * 字节跳动支付
     *
     * @param PayForm $payForm
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function byteDance(PayLog $payLog)
    {
        // 配置
        $config = [
            'notify_url' => $payLog->notify_url, // 支付通知回调地址
            'return_url' => $payLog->return_url, // 买家付款成功跳转地址
        ];

        // 生成订单
        $order = [
            'out_order_no' => $payLog->out_trade_no,
            'body' => $payLog->body,
            'subject' => $payLog->body,
            'total_amount' => (int)BcHelper::mul($payLog->total_fee, 100),
        ];

        return Yii::$app->pay->byteDance($config)->create($order);
    }

    /**
     * Stripe
     *
     * @param PayLog $payLog
     * @return mixed
     */
    public function stripe(PayLog $payLog)
    {
        if (empty($payLog->detail)) {
            throw new UnprocessableEntityHttpException('请检查填写信息是否正确');
        }

        // 生成订单
        $order = [
            'amount' => $payLog->total_fee < 1 ? 1 : $payLog->total_fee,
            'currency' => 'CAD',
            'token' => $payLog->detail,
            'description' => $payLog->body,
            'returnUrl' => $payLog->return_url,
            'metadata' => [
                'order_sn' => $payLog->order_sn,
                'out_trade_no' => $payLog->out_trade_no,
            ],
            // 'paymentMethod' => '',
            'confirm' => true,
        ];

        // 交易类型
        $tradeType = $payLog->trade_type;

        return Yii::$app->pay->stripe->$tradeType($order);
    }

    /**
     * 退款申请
     *
     * @param $order_sn
     * @param int $money
     * @throws UnprocessableEntityHttpException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function refundAll($order_sn, $money = 0)
    {
        $refundMoneyStatus = !empty($money);
        $models = $this->findAllByOrderSn($order_sn);
        /** @var PayLog $model */
        foreach ($models as $model) {
            // 退固定金额
            if ($refundMoneyStatus == true) {
                if ($money > 0) {
                    // 退款金额大于余额
                    if ($money > $model->pay_fee) {
                        $this->refund($model->pay_type, $model->pay_fee, $order_sn, $model);
                        $money = BcHelper::sub($money, $model->pay_fee);
                    } else {
                        $this->refund($model->pay_type, $money, $order_sn, $model);
                        $money = 0;
                    }
                }
            } else {
                $this->refund($model->pay_type, $model->pay_fee, $order_sn, $model);
            }
        }
    }

    /**
     * 订单退款
     *
     * @param $pay_type
     * @param $money
     * @param $out_trade_no
     * @throws UnprocessableEntityHttpException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function refund($pay_type, $money, $order_sn, $model = '')
    {
        /** @var PayLog $model */
        if (empty($model)) {
            $model = $this->findByOrderSn($order_sn);
        }

        if (!$model) {
            throw new UnprocessableEntityHttpException('找不到支付记录');
        }

        if ($model->pay_status == StatusEnum::DISABLED) {
            throw new UnprocessableEntityHttpException('未支付');
        }

        $residueMoney = BcHelper::sub($model->pay_fee, $this->getRefundMoneyByPayId($model->id));
        if ($money > $residueMoney) {
            throw new UnprocessableEntityHttpException('退款金额不可大于支付金额');
        }

        $refund_sn = date('YmdHis') . StringHelper::random(8, true);
        $response = [];
        // 字节跳动小程序退款
        if ($model['trade_type'] == 'byte-dance') {
            $response = Yii::$app->pay->byteDance->refund([
                'out_order_no' => $model->out_trade_no,
                'out_refund_no' => $refund_sn,
                'refund_amount' => $money * 100,
                'reason' => '不想要了',
            ]);
        } else {
            switch ($pay_type) {
                case PayTypeEnum::WECHAT :
                    $total_fee = $this->getTotalFeeByOutTradeNo($model->out_trade_no);
                    $info = [
                        'out_trade_no' => $model->out_trade_no,
                        'out_refund_no' => $refund_sn,
                        'amount' => [
                            'refund' => $money * 100,
                            'total' => (int) ($total_fee * 100),
                            'currency' => 'CNY',
                        ],
                    ];
                    $response = Yii::$app->pay->wechat->refund($info);
                    $response = $response->toArray();
                    if (isset($response['code'])) {
                        throw new UnprocessableEntityHttpException($response['message']);
                    }

                    break;
                case PayTypeEnum::ALI :
                    $info = [
                        'out_trade_no' => $model->out_trade_no,
                        'trade_no' => $model->transaction_id,
                        'refund_amount' => $money,
                        'out_request_no' => $refund_sn,
                    ];

                    $response = Yii::$app->pay->alipay->refund($info);
                    $response = $response->toArray();
                    if (isset($response['code'])) {
                        throw new UnprocessableEntityHttpException($response['message']);
                    }

                    break;
                case PayTypeEnum::UNION :
                    $info = [
                        'txnTime' => date('YmdHis'),
                        'origQryId' => $model->transaction_id,
                        'txnAmt' => $money,
                        'orderId' => $refund_sn,
                    ];

                    $response = Yii::$app->pay->unipay->refund($info);
                    $response = $response->toArray();
                    if (isset($response['code'])) {
                        throw new UnprocessableEntityHttpException($response['message']);
                    }
                    break;
            }
        }

        $model->refund_fee += $money;
        $model->save();

        $refund = new PayRefund();
        $refund = $refund->loadDefaultValues();
        $refund->pay_id = $model->id;
        $refund->app_id = Yii::$app->id;
        $refund->ip = Yii::$app->services->base->getUserIp();
        $refund->order_sn = $order_sn;
        $refund->merchant_id = $model->merchant_id;
        $refund->member_id = $model->member_id;
        $refund->refund_trade_no = $refund_sn;
        $refund->refund_money = $money;
        $refund->save();
    }

    /**
     * 支付通知回调
     *
     * @param PayLog $log
     * @param $paymentType
     * @throws \yii\web\NotFoundHttpException
     */
    public function notify(PayLog $log)
    {
        $log->pay_ip = Yii::$app->request->userIP;
        $log->save();

        switch ($log->order_group) {
            case 'order' :
                // TODO 处理订单

                // 记录消费日志
                Yii::$app->services->memberCreditsLog->consumeMoney(new CreditsLogForm([
                    'member' => Yii::$app->services->member->get($log->member_id),
                    'num' => $log->pay_fee,
                    'group' => 'order',
                    'pay_type' => $log->pay_type,
                    'remark' => "订单支付",
                    'map_id' => $log->id,
                ]));

                break;
            case 'recharge' :
                $payFee = $log['pay_fee'];
                $member = Yii::$app->services->member->get($log['member_id']);

                // 充值
                Yii::$app->services->memberCreditsLog->incrMoney(new CreditsLogForm([
                    'member' => $member,
                    'pay_type' => $log['pay_type'],
                    'num' => $payFee,
                    'group' => 'recharge',
                    'remark' => "在线充值",
                    'map_id' => $log['id'],
                ]));

                // TODO 赠送

                break;
        }
    }

    /**
     * @param $pay_id
     * @return bool|int|mixed|string|null
     */
    public function getRefundMoneyByPayId($pay_id)
    {
        $money = PayRefund::find()
            ->where(['pay_id' => $pay_id])
            ->sum('refund_money');

        return empty($money) ? 0 : $money;
    }

    /**
     * @param $outTradeNo
     * @return array|null|\yii\db\ActiveRecord|PayLog
     */
    public function findByOutTradeNo($outTradeNo)
    {
        return PayLog::find()
            ->where(['out_trade_no' => $outTradeNo])
            ->one();
    }

    /**
     * @param $outTradeNo
     * @return array|null|\yii\db\ActiveRecord|PayLog
     */
    public function findOrderSnByOutTradeNo($outTradeNo)
    {
        return PayLog::find()
            ->select(['order_sn'])
            ->where(['out_trade_no' => $outTradeNo])
            ->column();
    }

    /**
     * 获取订单总金额
     *
     * @param $outTradeNo
     * @return bool|int|mixed|string|null
     */
    public function getTotalFeeByOutTradeNo($outTradeNo)
    {
        return PayLog::find()
            ->select('pay_fee')
            ->where(['out_trade_no' => $outTradeNo])
            ->orderBy('id desc')
            ->scalar();
    }

    /**
     * @param $order_sn
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByOrderSn($order_sn)
    {
        return PayLog::find()
            ->where(['order_sn' => $order_sn])
            ->one();
    }

    /**
     * @param $order_sn
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findAllByOrderSn($order_sn)
    {
        return PayLog::find()
            ->where(['order_sn' => $order_sn, 'pay_status' => StatusEnum::ENABLED])
            ->all();
    }
}
