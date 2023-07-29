<?php

namespace addons\WechatMini\merchant\controllers;

use Yii;
use common\helpers\ArrayHelper;
use addons\WechatMini\merchant\forms\ConfigFrom;

/**
 * Class ConfigController
 * @package addons\WechatMini\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ConfigController extends BaseController
{
    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new ConfigFrom();
        $model->attributes = Yii::$app->services->config->backendConfigAll();
        if ($model->load(Yii::$app->request->post())) {
            $config = ArrayHelper::toArray($model);
            Yii::$app->services->config->updateAll(Yii::$app->id, 0, $config);

            return $this->message("保存成功", $this->redirect(Yii::$app->request->referrer));
        }

        return $this->render('index',[
            'model' => $model
        ]);
    }
}
