<?php

namespace merchant\controllers;

use Yii;

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
        // 触发主题切换
        !Yii::$app->params['isMobile'] && Yii::$app->services->theme->autoSwitcher();
        // 设置为 AJAX 关闭掉 DEBUG 显示
        YII_DEBUG && Yii::$app->request->headers->set('X-Requested-With', 'XMLHttpRequest');

        return $this->renderPartial('@backend/views/theme/' . Yii::$app->params['theme']['layout'] . '/index', [

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
