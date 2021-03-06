<?php

namespace services\rbac;

use common\enums\AppEnum;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use common\helpers\ArrayHelper;
use common\models\rbac\AuthItem;
use common\models\rbac\AuthItemChild;

/**
 * Class AuthItemService
 * @package services\rbac
 */
class AuthItemService
{
    /**
     * 卸载插件
     *
     * @param $name
     * @param bool $delAuthItemChild
     */
    public function delByAddonName($name, $delAuthItemChild = true)
    {
        AuthItem::deleteAll(['is_addon' => WhetherEnum::ENABLED, 'addon_name' => $name]);
        $delAuthItemChild == true && AuthItemChild::deleteAll(['is_addon' => WhetherEnum::ENABLED, 'addon_name' => $name]);
    }

    /**
     * 编辑下拉选择框数据
     *
     * @param $app_id
     * @param string $id
     * @return array
     */
    public function getDropDownForEdit($app_id, $id = '')
    {
        $list = AuthItem::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['app_id' => $app_id, 'is_addon' => WhetherEnum::DISABLED])
            ->select(['id', 'title', 'pid', 'level'])
            ->orderBy('sort asc')
            ->asArray()
            ->all();

        $list = ArrayHelper::removeByValue($list, $id);
        $models = ArrayHelper::itemsMerge($list);
        $data = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');

        return ArrayHelper::merge([0 => '顶级权限'], $data);
    }

    /**
     * @param array $ids
     * @param string $app_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByAppId($app_id = AppEnum::BACKEND, $ids = [])
    {
        return AuthItem::find()
            ->select(['id', 'title', 'name', 'pid', 'level', 'app_id', 'is_addon', 'addon_name'])
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['app_id' => $app_id])
            ->andFilterWhere(['in', 'id', $ids])
            ->orderBy('sort asc, id asc')
            ->asArray()
            ->all();
    }

    /**
     * 查询当前应用所有权限配置
     *
     * @param string $app_id 应用id
     * @param int $is_addons 是否插件
     * @param string $addons_name 插件名称
     */
    public function findAll($app_id = AppEnum::BACKEND)
    {
        return AuthItem::find()
            ->where(['app_id' => $app_id])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->orderBy('sort asc, created_at asc')
            ->asArray()
            ->all();
    }
}
