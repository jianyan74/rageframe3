<?php

namespace backend\controllers;

use Yii;

/**
 * Class ThemeController
 * @package backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ThemeController extends BaseController
{
    /**
     * @return \yii\web\Response
     */
    public function actionUpdate()
    {
        $layout = Yii::$app->request->get('layout');

        Yii::$app->services->theme->update($layout);

        return $this->redirect(['main/index']);
    }
}
