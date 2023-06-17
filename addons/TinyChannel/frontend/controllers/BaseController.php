<?php

namespace addons\TinyChannel\frontend\controllers;

use Yii;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\TinyChannel\frontend\controllers
 */
class BaseController extends AddonsController
{
    /**
     * @var string
     */
    public $layout = "@addons/TinyChannel/frontend/views/layouts/main";
}
