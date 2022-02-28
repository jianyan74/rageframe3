<?php

namespace api\modules\v1\controllers\common;

use Yii;
use common\traits\PayNotify;
use api\controllers\OnAuthController;

/**
 * 支付回调
 *
 * Class PayNotifyController
 * @package frontend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class PayNotifyController extends OnAuthController
{
    use PayNotify;

    /**
     * @var string[]
     */
    public $authOptional = ['wechat', 'alipay', 'union', 'byte-dance', 'stripe'];

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function beforeAction($action)
    {
        Yii::$app->params['triggerBeforeSend'] = false;

        return parent::beforeAction($action);
    }
}