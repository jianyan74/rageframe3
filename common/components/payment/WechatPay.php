<?php

namespace common\components\payment;

use yii\helpers\Json;
use yii\web\UnprocessableEntityHttpException;
use common\helpers\ArrayHelper;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Exception\InvalidResponseException;
use Yansongda\Pay\Contract\ConfigInterface;
use Yansongda\Pay\Exception\InvalidConfigException;
use Yansongda\Pay\Plugin\ParserPlugin;
use Yansongda\Pay\Plugin\Wechat\PreparePlugin;
use Yansongda\Pay\Plugin\Wechat\SignPlugin;
use Yansongda\Pay\Plugin\Wechat\WechatPublicCertsPlugin;

/**
 * 微信支付类
 *
 * Class WechatPay
 * @package common\components\payment
 */
class WechatPay
{
    protected $config;

    /**
     * WechatPay constructor.
     */
    public function __construct($config)
    {
        // 初始化
        Pay::config($config);

        $this->config = $config;
    }

    /**
     * @return array
     * @throws InvalidConfigException
     * @throws InvalidResponseException
     * @throws \Yansongda\Pay\Exception\ContainerException
     * @throws \Yansongda\Pay\Exception\InvalidParamsException
     * @throws \Yansongda\Pay\Exception\ServiceNotFoundException
     */
    public function getPublicCerts()
    {
        $params = $this->config['wechat']['default'];
        $data = Pay::wechat()->pay(
            [PreparePlugin::class, WechatPublicCertsPlugin::class, SignPlugin::class, ParserPlugin::class],
            $params
        )->get('data', []);

        foreach ($data as $item) {
            $certs[$item['serial_no']] = decrypt_wechat_resource($item['encrypt_certificate'], $params)['ciphertext'] ?? '';
        }

        $wechatConfig = get_wechat_config($params);
        $wechatConfig['wechat_public_cert_path'] = ((array) $wechatConfig['wechat_public_cert_path']) + ($certs ?? []);

        Pay::set(ConfigInterface::class, Pay::get(ConfigInterface::class)->merge([
            'wechat' => [$params['_config'] ?? 'default' => $wechatConfig->all()],
        ]));

        return $certs;
    }

    /**
     * @return \Yansongda\Supports\Collection
     * @throws \Yansongda\Pay\Exception\ContainerException
     * @throws \Yansongda\Pay\Exception\InvalidParamsException
     */
    public function callback()
    {
        return Pay::wechat()->callback();
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function success()
    {
        return Pay::wechat()->success();
    }

    /**
     * 微信APP支付网关
     *
     * @param $order
     * @return \Yansongda\Supports\Collection
     * @throws UnprocessableEntityHttpException
     */
    public function app($order)
    {
        try {
            $data = Pay::wechat()->app($order);
        } catch (InvalidResponseException $invalidResponseException) {
            $this->getError($invalidResponseException->extra);
        }

        return $data;
    }

    /**
     * 微信原生扫码支付支付网关
     *
     * @param array $order
     * @param bool $debug
     * @return mixed
     */
    public function scan($order)
    {
        try {
            $data = Pay::wechat()->scan($order);
        } catch (InvalidResponseException $invalidResponseException) {
            $this->getError($invalidResponseException->extra);
        }

        return $data;
    }

    /**
     * 微信公众号支付支付网关
     *
     * @param array $order
     * $order = [
     *      'out_trade_no' => time().'',
     *      'description' => 'subject-测试',
     *      'amount' => [
     *          'total' => 1,
     *      ],
     *      'payer' => [
     *          'openid' => 'onkVf1FjWS5SBxxxxxxxx',
     *      ],
     * ];
     * @param bool $debug
     * @return array
     */
    public function mp($order)
    {
        try {
            $data = Pay::wechat()->mp($order);
        } catch (InvalidResponseException $invalidResponseException) {
            $this->getError($invalidResponseException->extra);
        }

        $data = ArrayHelper::toArray($data);
        if (isset($data['timeStamp'])) {
            $data['timestamp'] = $data['timeStamp'];
            unset($data['timeStamp']);
        }

        return $data;
    }

    /**
     * 小程序支付
     *
     * @param $order
     * @param bool $debug
     * @return array|mixed|null
     */
    public function mini($order)
    {
        try {
            $data = Pay::wechat()->mini($order);
        } catch (InvalidResponseException $invalidResponseException) {
            $this->getError($invalidResponseException->extra);
        }

        $data = ArrayHelper::toArray($data);
        if (isset($data['timeStamp'])) {
            $data['timestamp'] = $data['timeStamp'];
            unset($data['timeStamp']);
        }

        return $data;
    }

    /**
     * 微信刷卡支付网关
     *
     * @param array $order
     *    [
     *        'body'              => 'The test order',
     *        'out_trade_no'      => date('YmdHis') . mt_rand(1000, 9999),
     *        'total_fee'         => 1, //=0.01,
     *        'auth_code'         => '',
     *     ]
     * @param bool $debug
     * @return mixed
     */
    public function pos($order)
    {

    }

    /**
     * 微信H5支付网关
     * @param array $order
     * $order = [
     *      'out_trade_no' => time().'',
     *      'description' => 'subject-测试',
     *      'amount' => [
     *          'total' => 1,
     *      ],
     *      'scene_info' => [
     *          'payer_client_ip' => '1.2.4.8',
     *          'h5_info' => [
     *              'type' => 'Wap',
     *      ]
     *   ],
     * ];
     * @param bool $debug
     * @return mixed
     */
    public function wap($order)
    {
        try {
            $data = Pay::wechat()->wap($order);
        } catch (InvalidResponseException $invalidResponseException) {
            $this->getError($invalidResponseException->extra);
        }

        return ArrayHelper::toArray($data);
    }

    /**
     * 关闭订单
     *
     * $order = [
     *      'out_trade_no' => '1217752501201407033233368018',
     *  ];
     *
     *  or
     *
     * $order = '1217752501201407033233368018';
     */
    public function close($order)
    {
        Pay::wechat()->close($order);
    }

    /**
     * 转账
     *
     * @param $order
     * @return mixed
     */
    public function transfer($order)
    {
        /** @var  $data */
        $result = Pay::wechat()->transfer($order);
        $data = ArrayHelper::toArray($result);
        if (isset($data['code'])) {
            throw new UnprocessableEntityHttpException($data['message']);
        }

        return $data;
    }

    /**
     * 查询订单
     *
     * $order = [
     *      'transaction_id' => '1217752501201407033233368018',
     *  ];
     *
     *  or
     *
     * $order = '1217752501201407033233368018';
     */
    public function find($order)
    {
        return Pay::wechat()->find($order);
    }

    /**
     * 查询退款订单
     *
     * @param $transaction_id
     */
    public function findRefund($transaction_id)
    {
        $order = [
            'transaction_id' => $transaction_id,
            '_type' => 'refund',
        ];

        return Pay::wechat()->find($order);
    }

    /**
     * 退款
     *
     * 订单类型
     *
     * @param $order
     * $order = [
     *      'out_trade_no' => '1514192025',
     *      'out_refund_no' => time(),
     *      'amount' => [
     *              'refund' => 1,
     *              'total' => 1,
     *              'currency' => 'CNY',
     *      ],
     * ];
     */
    public function refund($order)
    {
        return Pay::wechat()->refund($order);
    }

    /**
     * @param $error
     * @return mixed
     * @throws UnprocessableEntityHttpException
     */
    protected function getError($error)
    {
        if (is_array($error) && isset($error['code']) && isset($error['message'])) {
            throw new UnprocessableEntityHttpException($error['message']);
        }

        $extraBody = Json::decode($error['body']);
        throw new UnprocessableEntityHttpException($extraBody['message']);
    }
}
