<?php

namespace addons\Wechat\merchant\controllers;

use Yii;
use common\helpers\AddonHelper;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\Wechat\merchant\controllers
 */
class BaseController extends AddonsController
{
    /**
     * @var string
     */
     public $layout = "@backend/views/layouts/main";

     public function beforeAction($action)
     {
         AddonHelper::filePath();

         return parent::beforeAction($action);
     }
}
