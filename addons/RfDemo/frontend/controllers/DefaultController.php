<?php

namespace addons\RfDemo\frontend\controllers;

use Yii;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\RfDemo\frontend\controllers
 */
class DefaultController extends BaseController
{
    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index',[

        ]);
    }
}
