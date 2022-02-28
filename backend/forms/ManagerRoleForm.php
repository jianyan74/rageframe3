<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

/**
 * Class ManagerRoleForm
 * @package backend\forms
 */
class ManagerRoleForm extends Model
{
    public $id;

    /**
     * @var array
     */
    public $role_ids = [];

    /**
     * @var
     */
    public $roles;

    /**
     * @return \string[][]
     */
    public function rules()
    {
        return [
            ['role_ids', 'required'],
            ['role_ids', 'safe'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'role_ids' => '授权角色'
        ];
    }

    public function save()
    {
        $defaultRoleIds = array_keys($this->roles);
        $selectIds = [];
        foreach ($this->role_ids as $id) {
            if (in_array($id, $defaultRoleIds)) {
                $selectIds[] = $id;
            }
        }

        // 角色授权
        Yii::$app->services->rbacAuthAssignment->assign($selectIds, $this->id, Yii::$app->id);

        return true;
    }
}
