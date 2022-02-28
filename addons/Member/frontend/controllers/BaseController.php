<?php

namespace addons\Member\frontend\controllers;

use Yii;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\Member\frontend\controllers
 */
class BaseController extends AddonsController
{
    /**
     * @var string
     */
    public $layout = "@addons/Member/frontend/views/layouts/main";
}
