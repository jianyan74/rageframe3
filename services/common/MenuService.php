<?php

namespace services\common;

use Yii;
use yii\helpers\Json;
use yii\db\ActiveQuery;
use common\enums\StatusEnum;
use common\enums\AddonTypeEnum;
use common\enums\WhetherEnum;
use common\models\common\Menu;
use common\models\common\MenuCate;
use common\components\Service;
use common\helpers\Auth;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;

/**
 * Class MenuService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class MenuService extends Service
{
    /**
     * @param string $addon_name 插件名称
     */
    public function delByAddonName($addon_name)
    {
        Menu::deleteAll(['addon_name' => $addon_name]);
    }

    /**
     * @param MenuCate $cate
     */
    public function delByCate(MenuCate $cate)
    {
        Menu::deleteAll(['app_id' => $cate->app_id, 'addon_name' => $cate->addon_name]);
    }

    /**
     * 更新状态
     *
     * @param $addon_name
     * @return void
     */
    public function updateStatusByAddonName($addon_name, $status)
    {
        Menu::updateAll(['status' => $status], ['addon_name' => $addon_name, 'is_addon' => StatusEnum::ENABLED]);
    }

    /**
     * @param array $menus
     * @param MenuCate $cate
     * @param int $pid
     * @param int $level
     * @param Menu $parent
     */
    public function createByAddon(array $menus, MenuCate $cate, $pid = 0, $level = 1, $parent = '')
    {
        // 重组数组
        $menus = ArrayHelper::regroupMapToArr($menus);
        foreach ($menus as $menu) {
            $model = new Menu();
            $model->attributes = $menu;
            // 增加父级
            !empty($parent) && $model->setParent($parent);
            if ($model->params) {
                $params = [];
                foreach ($model->params as $key => $value) {
                    $params[] = [
                        'key' => $key,
                        'value' => $value,
                    ];
                }

                $model->params = $params;
            }

            $model->name = '';
            $model->url = '/' . StringHelper::toUnderScore($cate->addon_name) . '/'. $menu['name'];
            $model->pid = $pid;
            $model->level = $level;
            $model->cate_id = $cate->id;
            $model->app_id = $cate->app_id;
            $model->addon_name = $cate->addon_name;
            $model->addon_location = $cate->addon_location;
            $model->is_addon = $cate->is_addon;
            if (!$model->save()) {
                $this->error($model);
            }

            if (isset($menu['child']) && !empty($menu['child'])) {
                $this->createByAddon($menu['child'], $cate, $model->id, $model->level + 1, $model);
            }
        }
    }

    /**
     * @param $app_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAllByAuth($app_id)
    {
        $models = $this->findAll($app_id);
        $addons = Yii::$app->services->addons->findBrief();
        $addonCateId = Yii::$app->services->menuCate->findAddon($app_id);
        $addonMenus = [];
        $addons = ArrayHelper::arrayKey($addons, 'name');
        $addonGroup = Yii::$app->params['addonsGroup'];

        foreach ($models as $key => &$model) {
            // 验证开发模式
            if (isset($model['pattern'])) {
                !is_array($model['pattern']) && $model['pattern'] = Json::decode($model['pattern']);
                if (!empty($model['pattern']) && !in_array(Yii::$app->params['devPattern'], $model['pattern'])) {
                    unset($models[$key]);
                    continue;
                }
            }

            if (Auth::verify($model['url']) === false) {
                unset($models[$key]);
                continue;
            }

            if (!empty($model['url'])) {
                $params = Json::decode($model['params']);
                (empty($params) || !is_array($params)) && $params = [];
                $model['fullUrl'][] = $model['url'];

                foreach ($params as $param) {
                    if (!empty($param['key'])) {
                        $model['fullUrl'][$param['key']] = $param['value'];
                    }
                }
            } else {
                $model['fullUrl'] = '#';
            }

            // 如果是插件且位置在应用中心
            if (
                $model['is_addon'] == WhetherEnum::ENABLED &&
                $model['addon_location'] == AddonTypeEnum::ADDONS &&
                !isset($addonMenus[$model['addon_name']])
            ) {
                $model['pid'] = $addons[$model['addon_name']]['group'];
                $model['title'] = $addons[$model['addon_name']]['title'];;
                $model['cate_id'] = $addonCateId;
                $addonMenus[$model['addon_name']] = $model;
                unset($models[$key]);
            }
        }

        ////////////////////////////// 插件组别 ////////////////////////////////////

        // 重组类型
        $addonGroupKey = array_column($addonMenus, 'pid');
        foreach ($addonGroup as $key => &$item) {
            $item['id'] = $key;
            $item['pid'] = 0;
            $item['cate_id'] = $addonCateId;
            $item['fullUrl'] = '#';

            if (!in_array($key, $addonGroupKey)) {
                unset($addonGroup[$key]);
            }
        }

        $models = ArrayHelper::merge($models, $addonMenus);

        return ArrayHelper::merge($models, $addonGroup);
    }

    /**
     * 获取下拉
     *
     * @param MenuCate $menuCate
     * @param string $id
     * @return array
     */
    public function getDropDown($menuCate, $app_id, $id = '')
    {
        $list = Menu::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['app_id' => $app_id])
            ->andWhere(['is_addon' => $menuCate->is_addon])
            ->andFilterWhere(['addon_name' => $menuCate->addon_name])
            ->andFilterWhere(['<>', 'id', $id])
            ->select(['id', 'title', 'pid', 'level'])
            ->orderBy('cate_id asc, sort asc')
            ->asArray()
            ->all();

        $models = ArrayHelper::itemsMerge($list);
        $data = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');

        return ArrayHelper::merge([0 => '顶级菜单'], $data);
    }

    /**
     * @param $app_id
     * @param string $addon_name
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll($app_id, $addon_name = '')
    {
        $data = Menu::find()->where(['status' => StatusEnum::ENABLED]);
        // 关闭开发模式
        if (empty(Yii::$app->services->config->backendConfig('sys_dev'))) {
            $data = $data->andWhere(['dev' => StatusEnum::DISABLED]);
        }

        $models = $data
            ->andWhere(['app_id' => $app_id])
            ->andFilterWhere(['addon_name' => $addon_name])
            ->with(['cate' => function (ActiveQuery $query) use ($app_id) {
                return $query->andWhere(['app_id' => $app_id]);
            }])
            ->orderBy('sort asc, id asc')
            ->asArray()
            ->all();

        return $models;
    }
}
