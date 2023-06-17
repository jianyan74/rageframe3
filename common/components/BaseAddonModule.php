<?php

namespace common\components;

use Yii;
use yii\base\Module;
use common\enums\AppEnum;
use common\helpers\ExecuteHelper;

/**
 * Class BaseAddonModule
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class BaseAddonModule extends Module
{
    /**
     * 插件名称
     *
     * @var string
     */
    public $name;

    /**
     * 真实应用id
     *
     * @var string
     */
    public $app_id;

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function init()
    {
        parent::init();

        // 初始化模块
        $addon = Yii::$app->services->addons->initParams($this->name);
        // 初始化真实应用id
        Yii::$app->params['realAppId'] = $this->app_id;
        // 初始化命名空间
        $this->controllerNamespace = "addons\\$this->name\\$this->app_id\controllers";
        // 初始化默认路径
        if (!in_array($this->app_id, AppEnum::api())) {
            $this->setBasePath("@addons/$this->name/$this->app_id");
        }

        // 初始化子模块
        if (
            isset($addon->default_config[Yii::$app->id]['modules']) &&
            !empty($addon->default_config[Yii::$app->id]['modules'])
        ) {
            $this->setModules($addon->default_config[Yii::$app->id]['modules']);
        }

        if (!empty($addon['bootstrap'])) {
            ExecuteHelper::map($addon['bootstrap'], 'run', $addon);
        }
    }
}
