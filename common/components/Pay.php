<?php

namespace common\components;

use Yii;
use yii\base\Component;
use common\components\payment\AliPay;
use common\components\payment\UniPay;
use common\components\payment\WechatPay;
use common\components\payment\ByteDancePay;
use common\components\payment\Stripe;
use common\helpers\ArrayHelper;

/**
 * 支付组件
 *
 * Class Pay
 * @package common\components
 * @property \common\components\payment\WechatPay $wechat
 * @property \common\components\payment\AliPay $alipay
 * @property \common\components\payment\UniPay $unipay
 * @property \common\components\payment\ByteDancePay $byteDance
 * @property \common\components\payment\Stripe $stripe
 * @author jianyan74 <751393839@qq.com>
 */
class Pay extends Component
{
    /**
     * 默认配置
     *
     * @var array
     */
    protected $config = [
        'alipay' => [
            'default' => [
                // 必填-支付宝分配的 app_id
                'app_id' => '',
                // 必填-应用私钥 字符串或路径
                'app_secret_cert' => '',
                // 必填-应用公钥证书 路径
                'app_public_cert_path' => '', // /Users/yansongda/pay/cert/appCertPublicKey_2016082000295641.crt
                // 必填-支付宝公钥证书 路径
                'alipay_public_cert_path' => '', // /Users/yansongda/pay/cert/alipayCertPublicKey_RSA2.crt
                // 必填-支付宝根证书 路径
                'alipay_root_cert_path' => '', // /Users/yansongda/pay/cert/alipayRootCert.crt
                'return_url' => '', // https://yansongda.cn/alipay/return
                'notify_url' => '', // https://yansongda.cn/alipay/notify
                // 选填-第三方应用授权token
                'app_auth_token' => '',
                // 选填-服务商模式下的服务商 id，当 mode 为 Pay::MODE_SERVICE 时使用该参数
                'service_provider_id' => '',
                // 选填-默认为正常模式。可选为： MODE_NORMAL, MODE_SANDBOX, MODE_SERVICE
                'mode' => \Yansongda\Pay\Pay::MODE_NORMAL,
            ]
        ],
        'wechat' => [
            'default' => [
                // 必填-商户号，服务商模式下为服务商商户号
                'mch_id' => '',
                // 必填-商户秘钥
                'mch_secret_key' => '',
                // 必填-商户私钥 字符串或路径
                'mch_secret_cert' => '',
                // 必填-商户公钥证书路径
                'mch_public_cert_path' => '',
                // 必填
                'notify_url' => '',
                // 选填-公众号 的 app_id
                'mp_app_id' => '',
                // 选填-小程序 的 app_id
                'mini_app_id' => '',
                // 选填-app 的 app_id
                'app_id' => '',
                // 选填-合单 app_id
                'combine_app_id' => '',
                // 选填-合单商户号
                'combine_mch_id' => '',
                // 选填-服务商模式下，子公众号 的 app_id
                'sub_mp_app_id' => '',
                // 选填-服务商模式下，子 app 的 app_id
                'sub_app_id' => '',
                // 选填-服务商模式下，子小程序 的 app_id
                'sub_mini_app_id' => '',
                // 选填-服务商模式下，子商户id
                'sub_mch_id' => '',
                // 选填-微信公钥证书路径, optional，强烈建议 php-fpm 模式下配置此参数
                'wechat_public_cert_path' => [
                    // '45F59D4DABF31918AFCEC556D5D2C6E376675D57' => __DIR__.'/Cert/wechatPublicKey.crt',
                ],
                // 选填-默认为正常模式。可选为： MODE_NORMAL, MODE_SERVICE
                'mode' => \Yansongda\Pay\Pay::MODE_NORMAL,
            ]
        ],
        'unipay' => [
            'default' => [
                // 必填-商户号
                'mch_id' => '',
                // 必填-商户公私钥
                'mch_cert_path' => '',
                // 必填-商户公私钥密码
                'mch_cert_password' => '',
                // 必填-银联公钥证书路径
                'unipay_public_cert_path' => '',
                // 必填
                'return_url' => '',
                // 必填
                'notify_url' => '',
            ],
        ],
        'logger' => [
            'enable' => true,
            'file' => './logs/alipay.log',
            'level' => YII_DEBUG ? 'debug' : 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [ // optional
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
    ];

    /**
     * 公用配置
     *
     * @var
     */
    protected $rfConfig;

    public function init()
    {
        // 默认读后台配置可切换为根据商户来获取配置
        $this->rfConfig = Yii::$app->services->config->configAll();
        // 初始化支付宝配置
        $this->config['alipay']['default'] = ArrayHelper::merge($this->config['alipay']['default'], [
            // 必填-支付宝分配的 app_id
            'app_id' => $this->rfConfig['alipay_app_id'],
            // 必填-应用私钥 字符串或路径
            'app_secret_cert' => Yii::getAlias($this->rfConfig['alipay_app_secret_cert']),
            // 必填-应用公钥证书 路径
            'app_public_cert_path' => Yii::getAlias($this->rfConfig['alipay_app_public_cert_path']), // /Users/yansongda/pay/cert/appCertPublicKey_2016082000295641.crt
            // 必填-支付宝公钥证书 路径
            'alipay_public_cert_path' => Yii::getAlias($this->rfConfig['alipay_public_cert_path']), // /Users/yansongda/pay/cert/alipayCertPublicKey_RSA2.crt
            // 必填-支付宝根证书 路径
            'alipay_root_cert_path' => Yii::getAlias($this->rfConfig['alipay_root_cert_path']), // /Users/yansongda/pay/cert/alipayRootCert.crt
            'return_url' => '', // https://yansongda.cn/alipay/return
            'notify_url' => '', // https://yansongda.cn/alipay/notify
        ]);

        // 初始化微信配置
        $this->config['wechat']['default'] = ArrayHelper::merge($this->config['wechat']['default'], [
            // 必填-商户号，服务商模式下为服务商商户号
            'mch_id' => $this->rfConfig['wechat_mch_id'],
            // 必填-商户秘钥
            'mch_secret_key' => Yii::getAlias($this->rfConfig['wechat_mch_secret_key']),
            // 必填-商户私钥证书 字符串或路径
            'mch_secret_cert' => Yii::getAlias($this->rfConfig['wechat_mch_secret_cert']),
            // 必填-商户公钥证书路径
            'mch_public_cert_path' => Yii::getAlias($this->rfConfig['wechat_mch_public_cert_path']),
            // 必填
            'notify_url' => '',
            // 选填-公众号 的 app_id
            'mp_app_id' => $this->rfConfig['wechat_mp_app_id'],
            // 选填-小程序 的 app_id
            'mini_app_id' => $this->rfConfig['wechat_mini_app_id'],
            // 选填-app 的 app_id
            'app_id' => $this->rfConfig['wechat_app_id'], // 微信开放平台 APPID
            // 选填-合单 app_id
            'combine_app_id' => '',
            // 选填-合单商户号
            'combine_mch_id' => '',
            // 选填-服务商模式下，子公众号 的 app_id
            'sub_mp_app_id' => '',
            // 选填-服务商模式下，子 app 的 app_id
            'sub_app_id' => '',
            // 选填-服务商模式下，子小程序 的 app_id
            'sub_mini_app_id' => '',
            // 选填-服务商模式下，子商户id
            'sub_mch_id' => '',
            // 选填-微信公钥证书路径, optional，强烈建议 php-fpm 模式下配置此参数
            'wechat_public_cert_path' => [
                // '45F59D4DABF31918AFCEC556D5D2C6E376675D57' => __DIR__.'/Cert/wechatPublicKey.crt',
            ],
        ]);

        // 初始化银联配置
        $this->config['unipay']['default'] = ArrayHelper::merge($this->config['unipay']['default'], [
            // 必填-商户号
            'mch_id' => $this->rfConfig['unipay_mch_id'],
            // 必填-商户公私钥
            'mch_cert_path' => Yii::getAlias($this->rfConfig['unipay_mch_cert_path']),
            // 必填-商户公私钥密码
            'mch_cert_password' => $this->rfConfig['unipay_mch_cert_password'],
            // 必填-银联公钥证书路径
            'unipay_public_cert_path' => Yii::getAlias($this->rfConfig['unipay_public_cert_path']),
            // 必填
            'return_url' => '',
            // 必填
            'notify_url' => '',
        ]);

        // 日志目录
        $this->config['logger']['file'] = Yii::getAlias('@runtime') . '/logs/pay-' . date('Y-m-d') . '.log';
        // 强制覆盖配置
        $this->config['_force'] = true;

        parent::init();
    }

    /**
     * 支付宝支付
     *
     * @param array $config
     * @return AliPay
     * @throws \yii\base\InvalidConfigException
     */
    public function alipay(array $config = [])
    {
        !empty($config) && $this->config['alipay']['default'] = ArrayHelper::merge($this->config['alipay']['default'], $config);

        return new AliPay($this->config);
    }

    /**
     * 微信支付
     *
     * @param array $config
     * @return WechatPay
     */
    public function wechat(array $config = [])
    {
        !empty($config) && $this->config['wechat']['default'] = ArrayHelper::merge($this->config['wechat']['default'], $config);

        return new WechatPay($this->config);
    }

    /**
     * 银联支付
     *
     * @param array $config
     * @return UniPay
     * @throws \yii\base\InvalidConfigException
     */
    public function unipay(array $config = [])
    {
        !empty($config) && $this->config['unipay']['default'] = ArrayHelper::merge($this->config['unipay']['default'], $config);

        return new UniPay($this->config);
    }

    /**
     * 字节跳动支付
     *
     * @param array $config
     * @return ByteDancePay
     * @throws \yii\base\InvalidConfigException
     */
    public function byteDance(array $config = [])
    {
        return new ByteDancePay(ArrayHelper::merge([
            'app_id' => $this->rfConfig['byte_dance_mini_app_id'],
            'app_secret' => $this->rfConfig['byte_dance_mini_app_secret'],
            'app_salt' => $this->rfConfig['byte_dance_mini_app_salt'], // SALT
            'app_token' => $this->rfConfig['byte_dance_mini_app_token'], // SALT
            'notify_url' => '',
            'return_url' => '',
        ], $config));
    }

    /**
     * Stripe
     *
     * 测试的接口，在key 结尾加 _test 字符串.
     * Test card: 4000001240000000
     * @param array $config
     * @return Stripe
     * @throws \yii\base\InvalidConfigException
     */
    public function stripe(array $config = [])
    {
        return new Stripe(ArrayHelper::merge([
            'publishable_key' => $this->rfConfig['stripe_publishable_key'],
            'secret_key' => $this->rfConfig['stripe_secret_key'],
        ], $config));
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (\Exception $e) {
            if ($this->$name()) {
                return $this->$name([]);
            } else {
                throw $e->getPrevious();
            }
        }
    }
}
