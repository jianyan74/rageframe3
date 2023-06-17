<?php

namespace common\components\payment;

use Yansongda\Pay\Pay;

/**
 * 银联支付类
 *
 * Class UniPay
 * @package common\components\payment
 */
class UniPay
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
        return Pay::unipay()->callback();
    }


    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function success()
    {
        return Pay::unipay()->success();
    }

    /**
     * 网页支付
     *
     * @param $config
     *
     * 参数说明
     * $order = [
     *     'txnTime' => date('YmdHis'),
     *     'orderId' => date('YmdHis') . mt_rand(1000, 9999),
     *     'txnAmt'  => '0.01',
     * ]
     *
     * @return string
     */
    public function web($order)
    {
        return Pay::unipay()->web($order);
    }

    /**
     * H5支付
     *
     * @param $order
     * $order = [
     *     'txnTime' => date('YmdHis'),
     *     'orderId' => date('YmdHis') . mt_rand(1000, 9999),
     *     'txnAmt'  => '0.01',
     * ]
     * @return mixed
     */
    public function wap($order)
    {
        return Pay::unipay()->wap($order);
    }

    /**
     * 扫码支付
     *
     * @param $order
     * @return mixed
     */
    public function scan($order)
    {
        $result = Pay::unipay()->scan($order);

        return $result->qrCode; // 二维码 url
    }

    /**
     * 扫码收款
     *
     * $info = [
     *     'orderId' => '',
     *     'qrNo' => '', // 付款码
     *     'txnAmt' => 18.4, // 金额
     *     'txnTime' => date('YmdHis') // 时间
     *  ]
     *
     * @return mixed
     */
    public function pos(array $info)
    {
        return Pay::unipay()->pos($info);
    }

    /**
     * 订单查询
     *
     * $info = [
     *     'orderId' => '转账单号',
     *     'txnTime' => date('YmdHis') // 时间
     *  ]
     *
     * or
     *
     * $info = [
     *     'orderId' => '转账单号',
     *     'txnTime' => date('YmdHis') // 时间
     *     '_type' => 'qr_code', // 查询二维码支付订单
     *  ]
     *
     * @return mixed
     */
    public function find($info)
    {
        return Pay::unipay()->find($info);
    }

    /**
     * 退款
     *
     * $info = [
     *     'txnTime' => date('YmdHis')
     *     'txnAmt' => 18.4,
     *     'orderId' => 'The existing Order ID', // 商户订单号
     *     'origQryId' => date('YmdHis') . mt_rand(1000, 9999) // 原交易查询流水号
     *  ]
     */
    public function refund(array $info)
    {
        return Pay::unipay()->refund($info);
    }
}
