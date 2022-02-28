<?php

namespace api\modules\v1\controllers\member;

use Yii;
use yii\web\UnprocessableEntityHttpException;
use api\controllers\OnAuthController;
use common\helpers\ResultHelper;
use common\enums\CertificationTypeEnum;
use common\enums\MemberTypeEnum;
use common\models\member\Member;

/**
 * Class CertificationController
 * @package api\modules\v1\controllers\member
 */
class CertificationController extends OnAuthController
{
    /**
     * @var string
     */
    public $modelClass = '';

    /**
     * @return mixed|void|\yii\db\ActiveRecord
     * @throws \Exception
     */
    public function actionCreate()
    {
        $frontUrl = Yii::$app->request->post('identity_card_front');
        $backUrl = Yii::$app->request->post('identity_card_back');
        if (empty($frontUrl)) {
            return ResultHelper::json(422, '身份证正面不能为空');
        }

        if (empty($backUrl)) {
            return ResultHelper::json(422, '身份证背面不能为空');
        }

        try {
            $member_id = Yii::$app->user->identity->member_id;
            /** @var Member $member */
            $member = Yii::$app->services->member->findById($member_id);

            $model = Yii::$app->services->memberCertification->authentication($frontUrl, $backUrl);
            $model->member_id = $member_id;
            $model->member_type = MemberTypeEnum::MEMBER;
            $model->save() && $this->getError($model);
            $member->certification_type = CertificationTypeEnum::PERSONAGE;
            $member->save() && $this->getError($model);

            throw new UnprocessableEntityHttpException('认证失败 .');
        } catch (\Exception $e) {
            return ResultHelper::json(422, '认证失败');
        }
    }
}