<?php

namespace common\traits;

use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\helpers\FileHelper;
use common\helpers\WechatHelper;
use common\models\extend\PayLog;
use common\enums\PayTypeEnum;
use common\helpers\BcHelper;
use common\helpers\ResultHelper;

/**
 * 支付回调
 *
 * Trait PayNotify
 * @package common\traits
 */
trait PayNotify
{
    /**
     * 支付宝
     *
     * @return void
     */
    public function actionAlipay()
    {
        try {
            $result = Yii::$app->pay->alipay->callback();

            $message = Yii::$app->request->post();
            unset($message['app_id']);
            $message['pay_fee'] = $message['total_amount'];
            $message['transaction_id'] = $message['trade_no'];
            $message['mch_id'] = $message['auth_app_id'];

            // 日志记录
            FileHelper::writeLog($this->getLogPath('alipay'), Json::encode(ArrayHelper::toArray($message)));

            if ($this->pay($message)) {
                die('success');
            }

            die('fail');
        } catch (\Exception $e) {
            // 记录报错日志
            FileHelper::writeLog($this->getLogPath('error'), $e->getMessage());
            die('fail'); // 通知响应
        }
    }

    /**
     * 公用支付回调 - 微信(v3)
     *
     * @return bool|string
     */
    public function actionWechat()
    {
        try {
            $result = Yii::$app->pay->wechat->callback();
            // 记录日志
            FileHelper::writeLog($this->getLogPath('wechat'), Json::encode(ArrayHelper::toArray($result)));

            if (
                $result['event_type'] === 'TRANSACTION.SUCCESS' &&
                $result['resource_type'] === 'encrypt-resource' &&
                $this->pay($result['resource']['ciphertext'])
            ) {
                return Yii::$app->pay->wechat->success();
            }

            throw new UnprocessableEntityHttpException($result['summary']);
        } catch (\Exception $e) {
            // 记录日志
            FileHelper::writeLog($this->getLogPath('wechat-error-sign'), Json::encode($e->getMessage()).date('Y-m-d H:i:s'));
        }

        return WechatHelper::fail();
    }

    /**
     * 公用支付回调 - 银联
     */
    public function actionUnion()
    {
        $response = Yii::$app->pay->unipay->notify();
        if ($response->isPaid()) {
            //pay success
        } else {
            //pay fail
        }
    }

    /**
     * 字节跳动小程序支付
     *
     * @return array|false|mixed
     */
    public function actionByteDance()
    {
        $response = Json::decode(file_get_contents('php://input'));
        $logPath = $this->getLogPath('byte-dance');
        FileHelper::writeLog($logPath, Json::encode($response));
        if (!Yii::$app->pay->byteDance->isPaid($response)) {
            return false;
        }

        // 重新组合数据
        $originalMsg = Json::decode($response['msg']);
        $message = [];
        $message['out_trade_no'] = $originalMsg['cp_orderno']; // 开发者传入订单号
        $message['pay_type'] = $originalMsg['way']; // way 字段中标识了支付渠道：2-支付宝，1-微信
        $message['pay_fee'] = BcHelper::div($originalMsg['total_amount'], 100);
        $message['transaction_id'] = $originalMsg['payment_order_no'];
        $message['mch_id'] = $originalMsg['appid'];
        if (!$this->pay($message)) {
            return false;
        }

        return ResultHelper::json(200, '成功', [
            'err_no' => '0',
            'err_tips' => 'success',
        ]);
    }

    /**
     * 公用支付回调 - Stripe
     *
     * @return bool|string
     */
    public function actionStripe()
    {
        $response = Json::decode(file_get_contents('php://input'));
        unset($response['data']['object']['charges']);

        if ($response['data']['object']['status'] == 'succeeded') {
            $logPath = $this->getLogPath('stripe');
            FileHelper::writeLog($logPath, Json::encode(ArrayHelper::toArray($response)));
            $response['out_trade_no'] = $response['data']['object']['metadata']['out_trade_no'];
            $response['pay_fee'] = $response['data']['object']['amount'] / 100;
            if ($this->pay($response)) {
                http_response_code(200);
                die('success');
            }

            http_response_code(400);
            die('fail');
        } else {
            die('fail');
        }
    }

    /**
     * @param $message
     * @return bool
     */
    protected function pay($message)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!($payLog = Yii::$app->services->extendPay->findByOutTradeNo($message['out_trade_no']))) {
                throw new UnprocessableEntityHttpException('找不到支付信息');
            }

            // 支付完成
            if ($payLog->pay_status == StatusEnum::ENABLED) {
                return true;
            }

            unset($message['trade_type']);
            $payLog->attributes = $message;
            $payLog->pay_type == PayTypeEnum::WECHAT && $payLog->total_fee = $payLog->total_fee / 100;
            $payLog->pay_status = StatusEnum::ENABLED;
            $payLog->pay_time = time();
            if (!$payLog->save()) {
                throw new UnprocessableEntityHttpException('日志修改失败');
            }

            // 业务回调
            $this->notify($payLog);

            $transaction->commit();

            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $errorInfo = Yii::$app->services->base->getErrorInfo($e);
            $errorInfo = ArrayHelper::toArray($errorInfo);
            // 记录报错日志
            FileHelper::writeLog($this->getLogPath('error'), Json::encode($errorInfo));

            return false;
        }
    }

    /**
     * 支付回调
     *
     * @param PayLog $payLog
     * @throws NotFoundHttpException
     */
    public function notify(PayLog $payLog)
    {
        Yii::$app->services->extendPay->notify($payLog);
    }

    /**
     * @param $type
     * @return string
     */
    protected function getLogPath($type)
    {
        return Yii::getAlias('@runtime')."/pay-logs/".date('Y_m_d').'/'.$type.'.txt';
    }
}
