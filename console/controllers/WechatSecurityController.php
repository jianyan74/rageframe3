<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 * php ./yii wechat-security/run
 *
 * Class WechatSecurityController
 * @package console\controllers
 */
class WechatSecurityController extends Controller
{
    /**
     * 生成配置
     */
    public function actionRun()
    {
        $result = Yii::$app->wechat->payment->security->getPublicKey();

        file_put_contents(Yii::getAlias('@runtime') . '/public.pem', $result);
    }
}