<?php

namespace services\rbac;

use Yii;
use yii\db\ActiveQuery;
use common\components\Service;
use common\enums\WhetherEnum;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;
use common\helpers\TreeHelper;
use common\models\rbac\AuthItem;
use common\models\rbac\AuthItemChild;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class AuthItemChildService
 * @package services\rbac
 */
class AuthItemChildService extends Service
{
    /**
     * 当前的角色所有权限
     *
     * @var array
     */
    protected $allAuthNames = [];

    /**
     * 获取用户所有的权限 - 包含插件
     *
     * @param $roles
     * @return array
     */
    public function getAuthByRole($roles)
    {
        if (!empty($this->allAuthNames)) {
            return $this->allAuthNames;
        }

        // 获取当前角色的权限
        $allAuth = AuthItemChild::find()
            ->select(['addon_name', 'name'])
            ->where(['in', 'role_id', ArrayHelper::getColumn($roles, 'id')])
            ->asArray()
            ->all();

        $addonsName = [];
        foreach ($allAuth as $item) {
            !isset($addonsName[$item['addon_name']]) && $this->allAuthNames[] = $item['addon_name'];

            $this->allAuthNames[] = $item['name'];
            $addonsName[$item['addon_name']] = true;
        }

        return $this->allAuthNames;
    }

    /**
     * 授权
     *
     * @param int $role_id 角色ID
     * @param array $data 数据
     * @param string $is_addon 是否插件
     * @param string $app_id 应用ID
     * @throws \yii\db\Exception
     */
    public function accredit(int $role_id, array $data, int $is_addon, string $app_id)
    {
        // 删除原先所有权限
        AuthItemChild::deleteAll(['role_id' => $role_id, 'is_addon' => $is_addon]);
        if (empty($data)) {
            return;
        }

        $rows = [];
        $items = Yii::$app->services->rbacAuthItem->findByAppId($app_id, $data);
        foreach ($items as $value) {
            $rows[] = [
                $role_id,
                $value['id'],
                $value['name'],
                $value['app_id'],
                $value['is_addon'],
                $value['addon_name'],
            ];
        }

        $field = ['role_id', 'item_id', 'name', 'app_id', 'is_addon', 'addon_name'];
        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(AuthItemChild::tableName(), $field, $rows)->execute();
    }

    /**
     * @param $allAuthItem
     * @param $name
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     */
    public function accreditByAddon($allAuthItem, $name, $delAuthItemChild = false)
    {
        // 卸载权限
        Yii::$app->services->rbacAuthItem->delByAddonName($name, $delAuthItemChild);
        // 重组
        foreach ($allAuthItem as &$val) {
            $val = ArrayHelper::regroupMapToArr($val);
        }

        $defaultAuth = [];
        // 重组路由
        $allAuth = [];
        foreach ($allAuthItem as $key => $item) {
            $allAuth = ArrayHelper::merge($allAuth, $this->regroupByAddonsData($item, $name, $key));
        }

        // 创建权限
        $rows = $this->createByAddonData(ArrayHelper::merge($defaultAuth, $allAuth));
        // 批量写入数据
        $field = ['title', 'name', 'app_id', 'is_addon', 'addon_name', 'pid', 'level', 'sort', 'tree', 'created_at', 'updated_at'];
        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(AuthItem::tableName(), $field, $rows)->execute();

        unset($data, $allAuth, $installData, $defaultAuth);
    }

    /**
     * @param $item
     * @param $name
     * @param $app_id
     * @return mixed
     */
    protected function regroupByAddonsData($item, $name, $app_id)
    {
        foreach ($item as &$value) {
            $value['app_id'] = $app_id;
            $value['is_addon'] = WhetherEnum::ENABLED;
            $value['addon_name'] = $name;

            // 组合子级
            if (isset($value['child']) && !empty($value['child'])) {
                $value['child'] = $this->regroupByAddonsData($value['child'], $name, $app_id);
            }
        }

        return $item;
    }

    /**
     * @param array $data
     * @param int $pid
     * @param int $level
     * @param AuthItem $parent
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     */
    protected function createByAddonData(array $data, $pid = 0, $level = 1, $parent = '')
    {
        $rows = [];
        foreach ($data as $datum) {
            /** @var AuthItem $model */
            $model = new AuthItem();
            $model = $model->loadDefaultValues();
            $model->attributes = $datum;
            // 增加父级
            !empty($parent) && $model->setParent($parent);
            $model->pid = $pid;
            $model->level = $level;
            $model->name = '/' . StringHelper::toUnderScore($model->addon_name) . '/' . $model->name;
            !$model->validate() && $this->error($model);

            // 创建子权限
            if (isset($datum['child']) && !empty($datum['child'])) {
                // 有子权限的直接写入
                !$model->save() && $this->error($model);
                $rows = array_merge($rows, $this->createByAddonData($datum['child'], $model->id, $level++, $model));
            } else {
                $model->tree = !empty($parent) ?  $parent->tree . TreeHelper::prefixTreeKey($parent->id) : TreeHelper::defaultTreeKey();

                $rows[] = [
                    $model->title,
                    $model->name,
                    $model->app_id,
                    $model->is_addon,
                    $model->addon_name,
                    $pid,
                    $level,
                    $model->sort ?? 9999,
                    $model->tree,
                    time(),
                    time(),
                ];

                unset($model);
            }
        }

        return $rows;
    }

    /**
     * 获取某角色的所有权限
     *
     * @param $role_id
     * @return array
     */
    public function findItemByRoleId($role_id)
    {
        $role = Yii::$app->services->rbacAuthRole->findById($role_id);
        $auth = AuthItemChild::find()
            ->where(['role_id' => $role_id])
            ->with(['item' => function (ActiveQuery $activeQuery) use ($role) {
                return $activeQuery->andWhere(['app_id' => $role['app_id']]);
            }])
            ->asArray()
            ->all();

        return array_column($auth, 'item');
    }
}
