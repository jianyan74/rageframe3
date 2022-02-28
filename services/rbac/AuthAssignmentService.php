<?php

namespace services\rbac;

use Yii;
use common\components\Service;
use common\models\rbac\AuthAssignment;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class AuthAssignmentService
 * @package services\rbac
 */
class AuthAssignmentService extends Service
{
    /**
     * 分配角色
     *
     * @param array $role_ids 角色id
     * @param int $user_id 用户id
     * @param string $app_id 应用id
     * @throws UnprocessableEntityHttpException
     */
    public function assign(array $role_ids, int $user_id, string $app_id)
    {
        // 移除已有的授权
        AuthAssignment::deleteAll(['user_id' => $user_id, 'app_id' => $app_id]);
        if (empty($role_ids)) {
            return true;
        }

        foreach ($role_ids as $role_id) {
            $model = new AuthAssignment();
            $model->user_id = $user_id;
            $model->role_id = $role_id;
            $model->app_id = $app_id;

            !$model->save() && $this->error($model);
        }

        return true;
    }

    /**
     * 获取当前用户权限的下面的所有用户id
     *
     * @param $app_id
     * @return array
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function getChildIds($app_id)
    {
        if (Yii::$app->services->rbacAuth->isSuperAdmin()) {
            return [];
        }

        $roleIds = Yii::$app->services->rbacAuthRole->getRoleIds();
        $childRoles = Yii::$app->services->rbacAuthRole->findByLoginUser($app_id);
        $childRoleIds = array_keys($childRoles);
        if (!$childRoleIds) {
            return [-1];
        }

        foreach ($childRoleIds as $key => $childRoleId) {
            if (in_array($childRoleId, $roleIds)) {
                unset($childRoleIds[$key]);
            }
        }

        // 用户ID
        $userIds = $this->findUserIdByRoleId($childRoleIds);

        return !empty($userIds) ? $userIds : [-1];
    }

    /**
     * @param $app_id
     * @return array|int[]|\yii\db\ActiveRecord
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function getRoleChildMap($app_id)
    {
        $roleIds = Yii::$app->services->rbacAuthRole->getRoleIds();
        $childRoles = Yii::$app->services->rbacAuthRole->findByLoginUser($app_id);
        foreach ($childRoles as $key => $childRole) {
            if (in_array($key, $roleIds)) {
                unset($childRoles[$key]);
            }
        }

        return !empty($childRoles) ? $childRoles : [];
    }

    /**
     * @param $user_id
     * @param $app_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findRoleIdSByUserIdAndAppId($user_id, $app_id)
    {
        return AuthAssignment::find()
            ->select(['role_id'])
            ->where(['app_id' => $app_id])
            ->andWhere(['user_id' => $user_id])
            ->asArray()
            ->column();
    }

    /**
     * @param $user_id
     * @param $app_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findUserIdByRoleId($role_ids)
    {
        return AuthAssignment::find()
            ->where(['in', 'role_id', $role_ids])
            ->select('user_id')
            ->asArray()
            ->column();
    }

    /**
     * @param $role_ids
     * @param $user_ids
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByUserIds($user_ids)
    {
        return AuthAssignment::find()
            ->where(['in', 'user_id', $user_ids])
            ->asArray()
            ->all();
    }
}
