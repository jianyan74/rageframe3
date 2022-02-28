<?php

namespace common\components\payment;

use linslin\yii2\curl\Curl;
use yii\helpers\Json;
use yii\web\UnprocessableEntityHttpException;

/**
 * 字节跳动小程序
 *
 * Class ByteDancePay
 * @package common\components\payment
 */
class ByteDancePay
{
    const URL = 'https://developer.toutiao.com/';

    /**
     * UnionPay constructor.
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 创建支付订单
     *
     * @param array $params
     * @return bool|mixed|string
     * @throws \Exception
     */
    public function create(array $params)
    {
        $params['app_id'] = $this->config['app_id'];
        $params['valid_time'] = time() + 2 * 3600 * 24;
        $params['sign'] = $this->getSign($params);

        return $this->httpPost('api/apps/ecpay/v1/create_order', $params);
    }

    /**
     * 查询
     *
     * @param int $out_order_no 退款单号
     * @param string $thirdparty_id
     * @return bool|mixed|string
     * @throws \Exception
     */
    public function query($out_order_no, $thirdparty_id = '')
    {
        $params = [];
        $params['app_id'] = $this->config['app_id'];
        $params['out_order_no'] = $out_order_no;
        $params['thirdparty_id'] = $thirdparty_id;
        $params['sign'] = $this->getSign($params);

        return $this->httpPost('api/apps/ecpay/v1/query_order', $params);
    }

    /**
     * 退款
     *
     * @param $params
     * @return bool|mixed|string
     * @throws \Exception
     */
    public function refund(array $params)
    {
        $params['app_id'] = $this->config['app_id'];
        $params['sign'] = $this->getSign($params);

        return $this->httpPost('api/apps/ecpay/v1/create_refund', $params);
    }

    /**
     * 退款查询
     *
     * @param int $out_refund_no 退款单号
     * @param string $thirdparty_id
     * @return bool|mixed|string
     * @throws \Exception
     */
    public function queryRefund($out_refund_no, $thirdparty_id = '')
    {
        $params['app_id'] = $this->config['app_id'];
        $params['out_order_no'] = $out_refund_no;
        $params['thirdparty_id'] = $thirdparty_id;
        $params['sign'] = $this->getSign($params);

        return $this->httpPost('api/apps/ecpay/v1/query_refund', $params);
    }

    /**
     * 签名校验
     *
     * @param $params
     * @return bool
     */
    public function isPaid($params)
    {
        if ($params['type'] !== 'payment') {
            return false;
        }

        $sign = $params["msg_signature"];
        unset($params["msg_signature"]);
        unset($params["type"]);
        $params['token'] = trim($this->config['app_token']);
        sort($params, 2);
        $signStr = trim(implode('', $params));

        return sha1($signStr) === $sign;
    }

    /**
     * 计算签名
     *
     * @param $params
     * @return string
     */
    protected function getSign($params)
    {
        unset($params["sign"]);
        unset($params["app_id"]);
        unset($params["thirdparty_id"]);
        $paramArray = [];
        foreach ($params as $param) {
            $paramArray[] = trim($param);
        }
        $paramArray[] = trim($this->config['app_salt']);
        sort($paramArray, 2);
        $signStr = trim(implode('&', $paramArray));

        return md5($signStr);
    }

    /**
     * @param $url
     * @param array $params
     * @return bool|mixed|string
     * @throws \Exception
     */
    protected function httpPost($url, $body = [])
    {
        $curl = new Curl();
        $result = $curl->setRawPostData(Json::encode($body))->post(self::URL . $url);
        $result = Json::decode($result);
        if ($result['err_no'] == 0) {
            return $result['data'] ?? '';
        }

        throw new UnprocessableEntityHttpException($result['err_no'] . ':' . $result['err_tips']);
    }
}
