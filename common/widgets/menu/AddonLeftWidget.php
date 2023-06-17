<?php

namespace common\widgets\menu;

use Yii;
use yii\helpers\Json;
use yii\base\Widget;
use common\helpers\Auth;
use common\helpers\ArrayHelper;

/**
 * 模块菜单
 *
 * Class AddonLeftWidget
 * @package common\widgets\menu
 * @author jianyan74 <751393839@qq.com>
 */
class AddonLeftWidget extends Widget
{
    /**
     * @return string
     */
    public function run()
    {
        $addon = ArrayHelper::toArray(Yii::$app->params['addon']);
        $menus = Yii::$app->services->menu->findAll(Yii::$app->id, $addon['name']);
        if (!Yii::$app->services->rbacAuth->isSuperAdmin()) {
            $auth = Auth::getAuth();
            foreach ($menus as $kye => $menu) {
                // 移除无权限菜单
                if (Auth::verify($menu['url'], $auth) === false) {
                    unset($menus[$kye]);
                }

                !is_array($menu['pattern']) && $menu['pattern'] = Json::decode($menu['pattern']);
                if (!empty($menu['pattern']) && !in_array(Yii::$app->params['devPattern'], $menu['pattern'])) {
                    unset($menus[$kye]);
                }
            }
        }

        foreach ($menus as &$menu) {
            !is_array($menu['params']) && $menu['params'] = Json::decode($menu['params']);
            $params = [];
            if ($menu['params']) {
                foreach ($menu['params'] as $param) {
                    $params[$param['key']] = $param['value'];
                }
            }

            $menu['params'] = $params;
        }

        return $this->render('addon-left', [
            'addon' => $addon,
            'menus' => $menus,
        ]);
    }
}
