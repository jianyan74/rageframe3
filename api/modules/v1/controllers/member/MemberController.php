<?php

namespace api\modules\v1\controllers\member;

use Yii;
use yii\web\NotFoundHttpException;
use api\controllers\OnAuthController;
use common\models\member\Member;
use common\forms\MemberForm;
use common\helpers\ResultHelper;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;

/**
 * 会员接口
 *
 * Class MemberController
 * @package api\modules\v1\controllers\member
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class MemberController extends OnAuthController
{
    /**
     * @var Member
     */
    public $modelClass = MemberForm::class;

    /**
     * 个人中心
     *
     * @return array|null|\yii\data\ActiveDataProvider|\yii\db\ActiveRecord
     */
    public function actionIndex()
    {
        $member_id = Yii::$app->user->identity->member_id;

        $member = $this->modelClass::find()
            ->where(['id' => $member_id])
            ->with(['account', 'memberLevel'])
            ->asArray()
            ->one();

        return $member;
    }

    /**
     * 更新
     *
     * @param $id
     * @return bool|mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $data = Yii::$app->request->post();
        $data = ArrayHelper::filter($data, [
            'nickname',
            'head_portrait',
            'realname',
            'birthday',
            'province_id',
            'city_id',
            'area_id',
            'address',
            'qq',
            'email',
            'gender',
        ]);

        $model = $this->findModel($id);
        $model->attributes = $data;
        if (!$model->save()) {
            return ResultHelper::json(422, $this->getError($model));
        }

        return 'ok';
    }

    /**
     * @param $id
     * @return \yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || !($model = $this->modelClass::find()->where([
                'id' => Yii::$app->user->identity->member_id,
                'status' => StatusEnum::ENABLED,
            ])->andFilterWhere(['merchant_id' => $this->getMerchantId()])->one())) {
            throw new NotFoundHttpException('请求的数据不存在');
        }

        return $model;
    }

    /**
     * 权限验证
     *
     * @param string $action 当前的方法
     * @param null $model 当前的模型类
     * @param array $params $_GET变量
     * @throws \yii\web\BadRequestHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // 方法名称
        if (in_array($action, ['delete', 'view'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}
