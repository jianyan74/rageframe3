<?php

namespace addons\Member\merchant\controllers;

use Yii;
use common\models\base\SearchModel;
use common\enums\StatusEnum;
use common\enums\MemberTypeEnum;
use common\forms\MemberForm as Member;
use common\traits\MemberMobileSelect;
use common\traits\MerchantCurd;
use addons\Member\merchant\forms\RechargeForm;
use addons\Member\merchant\forms\MemberEditForm;
use addons\Member\merchant\forms\MemberCreateForm;

/**
 * 会员管理
 *
 * Class MemberController
 * @package addons\Member\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MemberController extends BaseController
{
    use MerchantCurd, MemberMobileSelect;

    /**
     * @var Member
     */
    public $modelClass = Member::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['realname', 'mobile'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['type' => MemberTypeEnum::MEMBER])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->with(['account', 'memberLevel', 'tag']);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', null);
        $model = $this->findEditForm($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->referrer();
        }

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\Exception
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findCreateForm($id);
        $model->merchant_id = Yii::$app->services->merchant->getNotNullId();
        $model->type = MemberTypeEnum::MEMBER;

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            // 记录行为
            Yii::$app->services->actionLog->create('memberUpdate', '更新用户账号密码', 0, [], false);

            // 验证密码
            if (!empty($model->password_hash)) {
                $model->password_hash = Yii::$app->security->generatePasswordHash($model->password_hash);
            }

            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        $model->password_hash = '';
        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 修改等级
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionUpdateLevel()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            // 记录行为
            Yii::$app->services->actionLog->create('memberUpdateLevel', '更新用户等级');

            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'levelMap' => Yii::$app->services->memberLevel->getMap(),
        ]);
    }

    /**
     * 积分/余额变更
     *
     * @param $id
     * @return mixed|string
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionRecharge($id)
    {
        $rechargeForm = new RechargeForm();
        $member = Yii::$app->services->member->findById($id);

        // ajax 校验
        $this->activeFormValidate($rechargeForm);
        if ($rechargeForm->load(Yii::$app->request->post())) {
            // 记录行为
            Yii::$app->services->actionLog->create('memberRecharge', '更新用户余额/积分/成长值');

            if (!$rechargeForm->save($member)) {
                return $this->message($this->getError($rechargeForm), $this->redirect(Yii::$app->request->referrer), 'error');
            }

            return $this->message('充值成功', $this->redirect(Yii::$app->request->referrer));
        }

        return $this->renderAjax($this->action->id, [
            'model' => $member,
            'rechargeForm' => $rechargeForm,
        ]);
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     */
    protected function findCreateForm($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || empty(($model = MemberCreateForm::findOne(['id' => $id, 'merchant_id' => $this->getMerchantId()])))) {
            $model = new MemberCreateForm();
            return $model->loadDefaultValues();
        }

        return $model;
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     */
    protected function findEditForm($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || empty(($model = MemberEditForm::findOne(['id' => $id, 'merchant_id' => $this->getMerchantId()])))) {
            $model = new MemberEditForm();
            return $model->loadDefaultValues();
        }

        return $model;
    }
}
