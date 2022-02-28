<?php

namespace backend\forms;

use Yii;
use yii\web\NotFoundHttpException;
use common\models\member\Member;
use common\enums\MemberTypeEnum;
use common\helpers\ArrayHelper;
use common\forms\ManagerMemberForm;

/**
 * Class ManagerRoleForm
 * @package backend\forms
 */
class ManagerCreateForm extends ManagerMemberForm
{
    /**
     * @var array
     */
    public $role_ids = [];

    public $username;

    public $password;

    /**
     * @var
     */
    public $roles;

    /**
     * @return \string[][]
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['password', 'username'], 'required'],
            [['password'], 'string', 'min' => 6],
            ['role_ids', 'required'],
            ['role_ids', 'safe'],
        ]);
    }

    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'username' => '账号',
            'password' => '密码',
            'role_ids' => '授权角色',
        ];
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function create()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $member = new Member();
            $member->username = $this->username;
            $member->type = MemberTypeEnum::MANAGER;
            $member->last_ip = Yii::$app->services->base->getUserIp();
            $member->last_time = time();
            $member->password_hash = Yii::$app->security->generatePasswordHash($this->password);;
            if (!$member->save()) {
                $this->addErrors($member->getErrors());
                throw new NotFoundHttpException('用户编辑错误');
            }

            // 角色授权
            $defaultRoleIds = array_keys($this->roles);
            $selectIds = [];
            foreach ($this->role_ids as $id) {
                if (in_array($id, $defaultRoleIds)) {
                    $selectIds[] = $id;
                }
            }

            Yii::$app->services->rbacAuthAssignment->assign($selectIds, $member->id, Yii::$app->id);

            $transaction->commit();

            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();

            return false;
        }
    }
}
