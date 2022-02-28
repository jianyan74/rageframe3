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
class BaseController extends AddonsController
{
    /**
     * @var string
     */
    public $layout = "@addons/RfDemo/frontend/views/layouts/main";
}
