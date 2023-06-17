<?php

namespace common\components;

use Yii;
use yii\base\Component;
use Doctrine\Common\Cache\PhpFileCache;
use Doctrine\Common\Cache\RedisCache;
use Qbhy\TtMicroApp\TtMicroApp;

/**
 * 抖音小程序
 *
 * Class ByteDance
 * @package common\components
 *
 * @property \Qbhy\TtMicroApp\TtMicroApp $miniProgram SDK实例
 *
 * @author jianyan74 <751393839@qq.com>
 */
class ByteDance extends Component
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

    public function initParams()
    {

    }

    /**
     * @return TtMicroApp
     */
    public function getMiniProgram()
    {
        $directory = Yii::getAlias("@app") . '/runtime/';
        $appConfig = [
            'debug' => true,
            'access_key' => Yii::$app->services->config->backendConfig('byte_dance_mini_app_id'),
            'secret_key' => Yii::$app->services->config->backendConfig('byte_dance_mini_app_secret'),
            'payment_app_id' => '',
            'payment_merchant_id' => '',
            'payment_secret' => '',
            // 'cache' => new PhpFileCache($directory), // 可选参数，你也可以用 \Doctrine\Common\Cache\ 下面得其他缓存驱动，比如 sqlite 等
            'cache' => new RedisCache(),
        ];

        return new TtMicroApp($appConfig);
    }
}
