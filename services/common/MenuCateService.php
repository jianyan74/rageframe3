<?php

namespace services\common;

use Yii;
use yii\helpers\Json;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use common\enums\AddonTypeEnum;
use common\helpers\ArrayHelper;
use common\helpers\Auth;
use common\components\Service;
use common\models\common\MenuCate;

/**
 * Class MenuCateService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class MenuCateService extends Service
{
    /**
     * @param $appId
     * @param array $info
     * @param $icon
     * @param $location
     * @param $sort
     * @param $pattern
     * @return MenuCate
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function createByAddon($appId, array $info, $icon, $location, $sort, $pattern = [])
    {
        MenuCate::deleteAll(['app_id' => $appId, 'addon_name' => $info['name']]);

        $model = new MenuCate();
        $model->app_id = $appId;
        $model->addon_name = $info['name'];
        $model->addon_location = $location;
        $model->is_addon = WhetherEnum::ENABLED;
        $model->title = $info['title'];
        $model->icon = $icon;
        $model->pattern = $pattern;
        $model->sort = $sort;
        if (!$model->save()) {
            $this->error($model);
        }

        return $model;
    }

    /**
     * 更新状态
     *
     * @param $addon_name
     * @return void
     */
    public function updateStatusByAddonName($addon_name, $status)
    {
        MenuCate::updateAll(['status' => $status], ['addon_name' => $addon_name, 'is_addon' => StatusEnum::ENABLED]);
    }

    /**
     * @param string $addon_name 插件名称
     */
    public function delByAddonName($addon_name)
    {
        MenuCate::deleteAll(['addon_name' => $addon_name]);
    }

    /**
     * 编辑 - 获取正常分类Map列表
     *
     * @return array
     */
    public function getDefaultMap($app_id)
    {
        return ArrayHelper::map($this->findDefault($app_id), 'id', 'title');
    }

    /**
     * @param $app_id
     * @return array|\yii\db\ActiveRecord[]
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function findAllInAuth($app_id)
    {
        $models = $this->findAll($app_id);
        foreach ($models as $key => $model) {
            if ($model['addon_location'] === AddonTypeEnum::ADDONS) {
                unset($models[$key]);
                continue;
            }

            // 验证开发模式
            if (!empty($model['pattern'])) {
                !is_array($model['pattern']) && $model['pattern'] = Json::decode($model['pattern']);
                if (
                    !empty($model['pattern']) &&
                    !in_array(Yii::$app->params['devPattern'], $model['pattern'])
                ) {
                    unset($models[$key]);
                    continue;
                }
            }

            if (
                $model['is_addon'] == WhetherEnum::DISABLED &&
                !Auth::verify('menuCate:' . $model['id'])
            ) {
                unset($models[$key]);
                continue;
            }

            if (
                $model['is_addon'] == WhetherEnum::ENABLED &&
                !Auth::verify($model['addon_name'])
            ) {
                unset($models[$key]);
            }
        }

        return $models;
    }

    /**
     * @param $id
     * @return MenuCate|null
     */
    public function findById($id)
    {
        return MenuCate::findOne($id);
    }

    /**
     * 查询 - 获取全部分类
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll($app_id)
    {
        return MenuCate::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['app_id' => $app_id])
            ->orderBy('sort asc, id asc')
            ->asArray()
            ->all();
    }

    /**
     * 编辑
     *
     *      获取正常的分类
     *
     * @param $app_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findDefault($app_id)
    {
        $list = MenuCate::find()
            ->where([
                'type' => StatusEnum::DISABLED,
                'app_id' => $app_id
            ])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['in', 'addon_location', ['', AddonTypeEnum::DEFAULT]])
            ->orderBy('sort asc, id asc')
            ->asArray()
            ->all();

//        foreach ($list as $key => $item) {
//            if (in_array(DevPatternEnum::BLANK, Json::decode($item['pattern']))) {
//                unset($list[$key]);
//            }
//        }

        return array_merge($list);
    }

    /**
     * 获取首个显示的分类
     *
     * @return false|null|string
     */
    public function findAddon($app_id)
    {
        return MenuCate::find()
            ->where(['status' => StatusEnum::ENABLED, 'type' => StatusEnum::ENABLED])
            ->andWhere(['app_id' => $app_id])
            ->orderBy('sort asc, id asc')
            ->select(['id'])
            ->scalar();
    }

    /**
     * 获取首个显示的分类
     *
     * @return false|null|string
     */
    public function findFirstId($app_id)
    {
        return MenuCate::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['app_id' => $app_id])
            ->andWhere(['in', 'addon_location', ['', AddonTypeEnum::DEFAULT]])
            ->orderBy('sort asc, id asc')
            ->select(['id'])
            ->scalar();
    }
}
