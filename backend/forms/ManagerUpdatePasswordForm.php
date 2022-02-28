<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use common\models\member\Member;

/**
 * Class ManagerUpdatePasswordForm
 * @package backend\forms
 */
class ManagerUpdatePasswordForm extends Model
{
    public $username;

    public $password;

    /**
     * @var Member
     */
    public $manager;

    /**
     * @return \string[][]
     */
    public function rules()
    {
        return [
            [['password', 'username'], 'required'],
            [['password'], 'string', 'min' => 6],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'username' => '账号',
            'password' => '密码',
        ];
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save()
    {
        try {
            $manager = $this->manager;
            $manager->password_hash = Yii::$app->security->generatePasswordHash($this->password);;
            if (!$manager->save()) {
                $this->addErrors($manager->getErrors());
                throw new NotFoundHttpException('用户编辑错误');
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
