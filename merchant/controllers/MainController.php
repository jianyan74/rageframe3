<?php

namespace merchant\controllers;

use Yii;
use yii\web\UnprocessableEntityHttpException;

/**
 * 主控制器
 *
 * Class MainController
 * @package merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MainController extends BaseController
{
    /**
     * @var string
     */
    public $layout = '@backend/views/layouts/main';

    /**
     * 系统首页
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderPartial('@backend/views/main/index', [
        ]);
    }

    /**
     * 子框架默认主页
     *
     * @return string
     */
    public function actionHome()
    {
        return $this->render($this->action->id, [
        ]);
    }
}
