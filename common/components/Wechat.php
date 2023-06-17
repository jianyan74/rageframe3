<?php

namespace common\components;

use Yii;
use common\helpers\FileHelper;
use common\enums\AppEnum;
use common\helpers\ArrayHelper;

/**
 * Class Wechat
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class Wechat extends \jianyan\easywechat\Wechat
{
    /**
     * Wechat constructor.
     * @param array $config
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->initParams();
    }

    /**
     * @param $config
     * @throws \yii\base\InvalidConfigException
     */
    protected function initParams()
    {
        $config = Yii::$app->services->config->configAll();

        $callbackUrl = $notifyUrl = '';
        if (!empty(Yii::$app->id) && Yii::$app->id != AppEnum::CONSOLE) {
            $callbackUrl = Yii::$app->request->hostInfo . Yii::$app->request->getUrl();
            $notifyUrl = Yii::$app->request->hostInfo . Yii::$app->urlManager->createUrl(['notify/index']);
        }

        // 微信公众号
        Yii::$app->params['wechatConfig'] = ArrayHelper::merge([
            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id' => $config['wechat_mp_app_id'] ?? '',
            'secret' => $config['wechat_mp_appsecret'] ?? '',
            'token' => $config['wechat_mp_token'] ?? '',
            'aes_key' => $config['wechat_mp_encodingaeskey'] ?? '', // 兼容与安全模式下请一定要填写！！！
            /**
             * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
             * 使用自定义类名时，构造函数将会接收一个 `EasyWeChat\Kernel\Http\Response` 实例
             */
            'response_type' => 'array',

            /**
             * 日志配置
             *
             * level: 日志级别, 可选为：
             *         debug/info/notice/warning/error/critical/alert/emergency
             * permission：日志文件权限(可选)，默认为null（若为null值,monolog会取0644）
             * file：日志文件位置(绝对路径!!!)，要求可写权限
             */
            'log' => [
                'default' => YII_DEBUG ? 'dev' : 'prod', // 默认使用的 channel，生产环境可以改为下面的 prod
                'channels' => [
                    // 测试环境
                    'dev' => [
                        'driver' => 'single',
                        'path' => $this->createLogPath('dev'),
                        'level' => 'debug',
                    ],
                    // 生产环境
                    'prod' => [
                        'driver' => 'daily',
                        'path' => $this->createLogPath('prod'),
                        'level' => 'info',
                    ],
                ],
            ],
            /**
             * 接口请求相关配置，超时时间等，具体可用参数请参考：
             * http://docs.guzzlephp.org/en/stable/request-config.html
             *
             * - retries: 重试次数，默认 1，指定当 http 请求失败时重试的次数。
             * - retry_delay: 重试延迟间隔（单位：ms），默认 500
             * - log_template: 指定 HTTP 日志模板，请参考：https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php
             */
            'http' => [
                'max_retries' => 1,
                'retry_delay' => 500,
                'timeout' => 5.0,
                // 'base_uri' => 'https://api.weixin.qq.com/', // 如果你在国外想要覆盖默认的 url 的时候才使用，根据不同的模块配置不同的 uri
            ],

            /**
             * OAuth 配置
             *
             * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
             * callback：OAuth授权完成后的回调页地址
             */
            'oauth' => [
                'scopes' => ['snsapi_userinfo'],
                'callback' => $callbackUrl,
            ]
        ], Yii::$app->params['wechatConfig']);

        // 微信支付
        Yii::$app->params['wechatPaymentConfig'] = ArrayHelper::merge([
            'app_id' => $config['wechat_mp_app_id'] ?? '',
            'secret' => $config['wechat_mp_appsecret'] ?? '',
            'mch_id' => $config['wechat_mch_id'] ?? '',
            'key' => $config['wechat_mch_secret_key'] ?? '', // API 密钥
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path' => $config['wechat_mch_public_cert_path'] ?? '', // XXX: 绝对路径！！！！
            'key_path' => $config['wechat_mch_secret_cert'] ?? '', // XXX: 绝对路径！！！！
            // 支付回调地址
            'notify_url' => $notifyUrl,
            'sandbox' => false, // 设置为 false 或注释则关闭沙箱模式
            'log' => [
                'default' => YII_DEBUG ? 'dev' : 'prod', // 默认使用的 channel，生产环境可以改为下面的 prod
                'channels' => [
                    // 测试环境
                    'dev' => [
                        'driver' => 'single',
                        'path' => $this->createLogPath('payment-dev'),
                        'level' => 'debug',
                    ],
                    // 生产环境
                    'prod' => [
                        'driver' => 'daily',
                        'path' => $this->createLogPath('payment-prod'),
                        'level' => 'info',
                    ],
                ],
            ],
        ], Yii::$app->params['wechatPaymentConfig']);

        // 小程序
        Yii::$app->params['wechatMiniProgramConfig'] = ArrayHelper::merge([
            'app_id' => $config['wechat_mini_app_id'] ?? '',
            'secret' => $config['wechat_mini_secret'] ?? '',
            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
            'log' => [
                'default' => YII_DEBUG ? 'dev' : 'prod', // 默认使用的 channel，生产环境可以改为下面的 prod
                'channels' => [
                    // 测试环境
                    'dev' => [
                        'driver' => 'single',
                        'path' => $this->createLogPath('miniProgram-dev'),
                        'level' => 'debug',
                    ],
                    // 生产环境
                    'prod' => [
                        'driver' => 'daily',
                        'path' => $this->createLogPath('miniProgram-prod'),
                        'level' => 'info',
                    ],
                ],
            ],
        ], Yii::$app->params['wechatMiniProgramConfig']);

        unset($config);
    }

    /**
     * 创建日志文件
     *
     * @param $path
     * @return bool
     */
    private function createLogPath($catalogue)
    {
        $logPathArr = [];
        $logPathArr[] = Yii::getAlias('@runtime');
        $logPathArr[] = 'wechat-' . $catalogue;
        $logPathArr[] = date('Y-m');

        $logPath = implode(DIRECTORY_SEPARATOR, $logPathArr);;
        FileHelper::mkdirs($logPath);

        $logPath .= DIRECTORY_SEPARATOR;
        $logPath .= date('d') . '.log';

        return $logPath;
    }
}
