<?php

namespace services\rbac;

use Yii;
use yii\web\UnauthorizedHttpException;
use common\enums\WhetherEnum;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\rbac\AuthRole;
use common\helpers\TreeHelper;

/**
 * Class AuthRoleService
 * @package services\rbac
 */
class AuthRoleService
{
    /**
     * 角色信息
     *
     * @var array
     */
    protected $roles = [];

    /**
     * 获取是否所有角色的条件
     *
     * @param bool $sourceAuthChild
     * @return array
     * @throws UnauthorizedHttpException
     */
    public function roleCondition($sourceAuthChild = false)
    {
        if ($sourceAuthChild == false || Yii::$app->services->rbacAuth->isSuperAdmin()) {
            return [];
        }

        $roles = Yii::$app->services->rbacAuthRole->getRoles();

        $where = [];
        $where[0] = 'or';
        $where[] = ['in', 'id', ArrayHelper::getColumn($roles, 'id')];
        foreach ($roles as $role) {
            $where[] = ['like', 'tree', $role['tree'] . TreeHelper::prefixTreeKey($role['id']) . '%', false];
            $where[] = ['like', 'tree', $role['tree'] . TreeHelper::prefixTreeKey($role['id']) . '%', false];
        }

        return $where;
    }

    /**
     * 获取当前登录的角色ID
     *
     * @return array
     * @throws UnauthorizedHttpException
     */
    public function getRoleIds()
    {
        return ArrayHelper::getColumn($this->getRoles(), 'id');
    }

    /**
     * 获取当前登录角色信息
     *
     * @return array|\yii\db\ActiveRecord[]
     * @throws UnauthorizedHttpException
     */
    public function getRoles()
    {
        if (Yii::$app->services->rbacAuth->isSuperAdmin()) {
            return [];
        }

        if (!$this->roles) {
            /* @var $assignment \common\models\rbac\AuthAssignment */
            if (empty($assignment = Yii::$app->user->identity->assignment ?? '')) {
                Yii::$app->user->logout();
                throw new UnauthorizedHttpException('未授权角色，请联系管理员');
            }

            $assignment = ArrayHelper::toArray($assignment);
            $this->roles = AuthRole::find()
                ->where(['in', 'id', ArrayHelper::getColumn($assignment, 'role_id')])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->asArray()
                ->all();

            if (!$this->roles) {
                throw new UnauthorizedHttpException('授权的角色已失效，请联系管理员');
            }
        }

        return $this->roles;
    }

    /**
     * 获取编辑的数据
     *
     * @param int $role_id
     * @param array $allAuth
     * @return array
     *
     */
    public function getJsTreeData($role_id, array $allAuth)
    {
        // 当前角色已有的权限
        $userAuth = Yii::$app->services->rbacAuthItemChild->findItemByRoleId($role_id);
        $addonName = $formAuth = $checkIds = $addonFormAuth = $addonsCheckIds = [];

        // 区分默认和插件权限
        foreach ($allAuth as $item) {
            if ($item['is_addon'] == WhetherEnum::DISABLED) {
                $formAuth[] = $item;
            } else {
                if ($item['pid'] == 0) {
                    $item['pid'] = $item['addon_name'];
                }

                $addonFormAuth[] = $item;
                $addonName[] = $item['addon_name'];
            }
        }

        // 获取顶级插件数据
        $addons = Yii::$app->services->addons->findByNames($addonName);
        foreach ($addons as $addon) {
            $addonFormAuth[] = [
                'id' => $addon['name'],
                'pid' => 0,
                'title' => $addon['title'],
            ];
        }

        // 区分默认和插件权限ID
        foreach ($userAuth as $value) {
            if (empty($value)) {
                continue;
            }

            if ($value['is_addon'] == WhetherEnum::DISABLED) {
                $checkIds[] = $value['id'];
            } else {
                $addonsCheckIds[] = $value['id'];
            }
        }

        return [$formAuth, $checkIds, $addonFormAuth, $addonsCheckIds];
    }

    /**
     * 获取上级角色
     *
     * @param $appId
     * @param false $sourceAuthChild
     * @param string $id
     * @return array
     * @throws UnauthorizedHttpException
     */
    public function getDropDownForEdit($appId, $sourceAuthChild = false, $id = '', $defaultData = [])
    {
        $list = $this->findAll($appId, Yii::$app->services->merchant->getNotNullId(), $this->roleCondition($sourceAuthChild));
        $list = ArrayHelper::merge($list, $defaultData);
        $list = ArrayHelper::removeByValue($list, $id);
        $list = ArrayHelper::arrayKey($list, 'id');
        $list = ArrayHelper::arraySort($list, 'level');
        foreach ($list as &$item) {
            if (!isset($list[$item['pid']])) {
                $item['pid'] = 0;
                $item['level'] = 1;
            }

            if ($item['pid'] > 0 && isset($list[$item['pid']])) {
                $item['level'] = $list[$item['pid']]['level'] + 1;
            }
        }

        $models = ArrayHelper::itemsMerge($list);
        $data = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');

        if (Yii::$app->services->rbacAuth->isSuperAdmin()) {
            return ArrayHelper::merge([0 => '顶级角色'], $data);
        }

        return $data;
    }

    /**
     * @param $app_id
     * @param $merchant_id
     * @param array $condition
     * @return array
     */
    public function getMapList($app_id, $merchant_id, $condition = [])
    {
        $list = $this->findAll($app_id, $merchant_id, $condition);

        $data = [];
        foreach ($list as $item) {
            $data[$item['id'] ] = $item['title'];
            if ($item['annual_fee'] > 0) {
                $data[$item['id'] ] = $item['title'] . ' | ' . '年费(' . $item['annual_fee'] . ')';
            }
        }

        return $data;
    }

    /**
     * @param $app_id
     * @return array
     * @throws UnauthorizedHttpException
     */
    public function findByLoginUser($app_id)
    {
        $roles = $this->findAll($app_id, Yii::$app->services->merchant->getNotNullId(), $this->roleCondition(true));

        return ArrayHelper::map($roles, 'id', 'title');
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findById($id)
    {
        return AuthRole::find()
            ->where(['id' => $id])
            ->asArray()
            ->one();
    }

    /**
     * 查询所有角色信息
     *
     * @return array
     */
    public function findAll($app_id, $merchant_id, $condition = []): array
    {
        return AuthRole::find()
            ->where(['app_id' => $app_id])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $merchant_id])
            ->andFilterWhere($condition)
            ->orderBy('sort asc, created_at asc')
            ->asArray()
            ->all();
    }
}
