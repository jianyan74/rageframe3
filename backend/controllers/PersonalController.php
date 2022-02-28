<?php

namespace backend\controllers;

use Yii;
use common\enums\AppEnum;
use common\models\member\Member;
use common\helpers\ResultHelper;
use backend\forms\PersonalPasswdForm;
use common\forms\ManagerMemberForm;
use common\forms\MerchantMemberForm;

/**
 * Class PersonalController
 * @package backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class PersonalController extends BaseController
{
    /**
     * 个人中心
     *
     * @return mixed|string
     */
    public function actionIndex()
    {
        /** @var Member $model */
        if (Yii::$app->id == AppEnum::BACKEND) {
            $model = ManagerMemberForm::findOne(Yii::$app->user->id);
        } else {
            $model = MerchantMemberForm::findOne(Yii::$app->user->id);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->message('个人信息修改成功', $this->redirect(['index']));
        }

        return $this->render('@backend/views/personal/index', [
            'model' => $model,
        ]);
    }

    /**
     * 修改密码
     *
     * @return array|string
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdatePassword()
    {
        $model = new PersonalPasswdForm();
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->validate()) {
                return ResultHelper::json(404, $this->getError($model));
            }

            /* @var $member Member */
            $member = Yii::$app->user->identity;
            $member->password_hash = Yii::$app->security->generatePasswordHash($model->passwd_new);;

            if ($member->save()) {
                Yii::$app->user->logout();

                return ResultHelper::json(200, '修改成功');
            }

            return ResultHelper::json(404, $this->analyErr($member->getFirstErrors()));
        }

        return $this->render('@backend/views/personal/update-password', [
            'model' => $model,
        ]);
    }
}
