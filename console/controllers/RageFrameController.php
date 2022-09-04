<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;

/**
 * 在线更新
 *
 * php ./yii rage-frame/update
 *
 * Class RageFrameController
 * @package console\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class RageFrameController extends Controller
{
    /**
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionUpdate()
    {
        Console::output('updating...');

        try {
            Yii::$app->services->addons->upgradeSql('Authority');
        } catch (\Exception $e) {
            Console::output($e->getMessage());

            return false;
        }

        return Console::output('update completed...');
    }
}
