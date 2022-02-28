<?php

namespace addons\Authority\merchant\controllers;

use Yii;
use common\helpers\ArrayHelper;
use addons\Authority\common\models\SettingForm;

/**
 * 参数设置
 *
 * Class SettingController
 * @package addons\Authority\merchant\controllers
 */
class SettingController extends BaseController
{
    /**
     * @return mixed|string
     */
    public function actionDisplay()
    {
        $request = Yii::$app->request;
        $model = new SettingForm();
        $model->attributes = $this->getConfig();
        if ($model->load($request->post()) && $model->validate()) {
            $this->setConfig(ArrayHelper::toArray($model));
            return $this->message('修改成功', $this->redirect(['display']));
        }

        return $this->render('display',[
            'model' => $model,
        ]);
    }
}
