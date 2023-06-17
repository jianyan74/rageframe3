<?php

namespace addons\Wechat\merchant\controllers;

use Yii;
use common\helpers\ArrayHelper;
use addons\Wechat\merchant\forms\ConfigFrom;
use addons\Wechat\merchant\forms\HistoryForm;

/**
 * Class ConfigController
 * @package addons\Wechat\merchant\controllers
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

        $historyForm = new HistoryForm();
        $historyForm->attributes = Yii::$app->services->addonsConfig->getConfig();

        if ($model->load(Yii::$app->request->post()) && $historyForm->load(Yii::$app->request->post())) {
            // 基本信息
            Yii::$app->services->config->updateAll(Yii::$app->id, 0, ArrayHelper::toArray($model));
            // 插件信息
            Yii::$app->services->addonsConfig->setConfig(ArrayHelper::toArray($historyForm));

            return $this->message("保存成功", $this->redirect(Yii::$app->request->referrer));
        }

        return $this->render('index',[
            'model' => $model,
            'historyForm' => $historyForm
        ]);
    }
}
