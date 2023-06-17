<?php

namespace common\components\payment;

use common\helpers\ArrayHelper;
use yii\web\UnprocessableEntityHttpException;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Exception\InvalidResponseException;

/**
 * Class AliPay
 * @doc https://pay.yansongda.cn/docs/v3/
 * @package common\components\payment
 */
class AliPay
{
    public function __construct($config)
    {
        // 初始化
        Pay::config($config);
    }

    /**
     * @return \Yansongda\Supports\Collection
     * @throws \Yansongda\Pay\Exception\ContainerException
     * @throws \Yansongda\Pay\Exception\InvalidParamsException
     */
    public function callback()
    {
        return Pay::alipay()->callback();
    }


    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function success()
    {
        return Pay::alipay()->success();
    }

    /**
     * 网页支付
     *
     * @param $config
     *
     * 参数说明
     * $config = [
     *     'subject'      => 'test',
     *     'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
     *     'total_amount' => '0.01',
     * ]
     *
     * @return string
     */
    public function web($order)
    {
        return Pay::alipay()->web($order)->getBody()->getContents();
    }

    /**
     * H5支付
     *
     * @param $order
     * @return mixed
     */
    public function wap($order)
    {
        return Pay::alipay()->wap($order)->getBody()->getContents();
    }


    /**
     * 小程序
     *
     * @param $order
     * @return mixed
     */
    public function mini($order)
    {
        $result = Pay::alipay()->mini($order);

        return $result->get('trade_no');  // 支付宝交易号
    }

    /**
     * APP支付
     *
     * 参数说明
     * $config = [
     *     'subject'      => 'test',
     *     'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
     *     'total_amount' => '0.01',
     * ]
     *
     * iOS 客户端
     * [[AlipaySDK defaultService] payOrder:orderString fromScheme:appScheme callback:^(NSDictionary *resultDic) {
     *      NSLog(@"reslut = %@",resultDic);
     * }];
     *
     * Android 客户端
     * PayTask alipay = new PayTask(PayDemoActivity.this);
     * Map<String, String> result = alipay.payV2(orderString, true);
     * @param $config
     * @param $notifyUrl
     * @return mixed
     */
    public function app($order)
    {
        return Pay::alipay()->app($order)->getBody()->getContents();
    }

    /**
     * 扫码支付
     *
     * @param $order
     * @return mixed
     */
    public function scan($order)
    {
        $result = Pay::alipay()->scan($order);

        $this->getError(ArrayHelper::toArray($result));

        return $result->qr_code; // 二维码 url
    }

    /**
     * 扫码收款
     *
     * $info = [
     *     'out_trade_no' => '',
     *     'auth_code' => '', // 付款码
     *     'total_amount' => 18.4, // 金额
     *     'subject' => '' // 说明
     *  ]
     *
     * @return mixed
     */
    public function pos(array $info)
    {
        return Pay::alipay()->pos($info);
    }

    /**
     * 转账
     *
     * $info = [
     *     'out_biz_no' => '转账单号',
     *     'trans_amount' => '收款金额',
     *     'payee_info' => [
     *          'identity_type' => 'ALIPAY_LOGON_ID', // ALIPAY_USER_ID:支付宝唯一号;ALIPAY_LOGON_ID:支付宝登录号
     *          'identity' => '收款人账号',
     *          'name' => '收款方真实姓名', // 非必填
     *     ],
     *     'remark' => '账业务的标题，用于在支付宝用户的账单里显示', // 非必填
     *     'order_title' => '转账业务的标题，用于在支付宝用户的账单里显示 ', // 非必填
     *  ]
     *
     * identity_type
     *     1、ALIPAY_USER_ID ：支付宝账号对应的支付宝唯一用户号。以2088开头的16位纯数字组成。
     *     2、ALIPAY_LOGON_ID：支付宝登录号，支持邮箱和手机号格式。
     *
     * @return mixed
     */
    public function transfer(array $info)
    {
        !isset($info['product_code']) && $info['product_code'] = 'TRANS_ACCOUNT_NO_PWD';
        !isset($info['biz_scene']) && $info['biz_scene'] = 'DIRECT_TRANSFER';
        !isset($info['payee_info']['identity_type']) && $info['payee_info']['identity_type'] = 'ALIPAY_LOGON_ID';

        return Pay::alipay()->transfer($info);
    }

    /**
     * 订单查询
     *
     * $info = [
     *     'out_biz_no' => '转账单号',
     *  ]
     *
     * or
     *
     * $info = '1514027114';
     * @return mixed
     */
    public function find($info)
    {
        return Pay::alipay()->find($info);
    }

    /**
     * 退款
     *
     * $info = [
     *     'out_trade_no' => 'The existing Order ID',
     *     'trade_no' => 'The Transaction ID received in the previous request',
     *     'refund_amount' => 18.4,
     *     'out_request_no' => date('YmdHis') . mt_rand(1000, 9999)
     *  ]
     */
    public function refund(array $info)
    {
        return Pay::alipay()->refund($info);
    }

    /**
     * @param $error
     * @return mixed
     * @throws UnprocessableEntityHttpException
     */
    protected function getError($error)
    {
        if (!is_array($error)) {
            return false;
        }

        if ($error['code'] != '10000') {
            if (isset($error['sub_msg'])) {
                throw new UnprocessableEntityHttpException($error['sub_msg']);
            }

            throw new UnprocessableEntityHttpException($error['msg']);
        }

        return true;
    }
}
