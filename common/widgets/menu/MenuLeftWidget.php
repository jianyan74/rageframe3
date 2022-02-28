<?php

namespace common\widgets\menu;

use Yii;
use yii\base\Widget;
use common\helpers\ArrayHelper;

/**
 * 左边菜单
 *
 * Class MenuLeftWidget
 * @package common\widgets\menu
 * @author jianyan74 <751393839@qq.com>
 */
class MenuLeftWidget extends Widget
{
    /**
     * @var string
     */
    public $app_id;

    /**
     * @return string
     */
    public function run()
    {
        empty($this->app_id) && $this->app_id = Yii::$app->id;

        $menus = Yii::$app->services->menu->findAllByAuth($this->app_id);

        return $this->render('menu-tree', [
            'menus' => ArrayHelper::itemsMerge($menus),
            'level' => 1,
        ]);
    }
}
