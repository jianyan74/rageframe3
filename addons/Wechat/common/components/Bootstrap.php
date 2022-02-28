<?php

namespace addons\Wechat\common\components;

use Yii;
use common\interfaces\AddonWidget;

/**
 * Bootstrap
 *
 * Class Bootstrap
 * @package addons\Wechat\common\config
 */
class Bootstrap implements AddonWidget
{
    /**
     * @param $addon
     * @return mixed|void
     */
    public function run($addon)
    {
        /** ------ 微信自定义接口配置------ **/
        Yii::$app->params['userApiPath'] = Yii::getAlias('@root') . '/addons/Wechat/common/userapis'; // 自定义接口路径
        Yii::$app->params['userApiNamespace'] = '\addons\Wechat\common\userapis'; // 命名空间
        Yii::$app->params['userApiCachePrefixKey'] = 'wechat:reply:user-api:'; // 缓存前缀
    }
}
