<?php

namespace addons\TinyBlog\frontend\controllers;

use Yii;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\TinyBlog\frontend\controllers
 */
class BaseController extends AddonsController
{
    /**
     * @var string
     */
    public $layout = "@addons/TinyBlog/frontend/views/layouts/main";
}
