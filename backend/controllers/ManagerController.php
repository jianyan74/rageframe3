<?php

namespace backend\controllers;

use Yii;
use common\enums\AppEnum;
use common\traits\Curd;
use common\enums\MemberTypeEnum;
use common\enums\StatusEnum;
use common\models\member\Member;
use common\models\base\SearchModel;
use common\forms\ManagerMemberForm;
use common\helpers\ResultHelper;
use backend\forms\ManagerRoleForm;
use backend\forms\ManagerCreateForm;
use backend\forms\ManagerUpdatePasswordForm;

/**
 * Class ManagerController
 * @package backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ManagerController extends BaseController
{
    use Curd;

    /**
     * @var ManagerMemberForm
     */
    public $modelClass = ManagerMemberForm::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        // 获取当前用户权限的下面的所有用户id，除超级管理员
        $memberIds = Yii::$app->services->rbacAuthAssignment->getChildIds(AppEnum::BACKEND);

        $searchModel = new SearchModel([
            'model' => Member::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['username'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_ASC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['type' => MemberTypeEnum::MANAGER])
            ->andFilterWhere(['in', 'id', $memberIds])
            ->with(['assignment.role']);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * ajax编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionCreate()
    {
        $model = new ManagerCreateForm();
        $model->roles = Yii::$app->services->rbacAuthAssignment->getRoleChildMap(AppEnum::BACKEND);
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->create()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
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
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return ResultHelper::json(200, 'ok');
            }

            return ResultHelper::json(422, $this->getError($model));
        }

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * ajax编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionUpdatePassword($id)
    {
        $manager = $this->findModel($id);
        $model = new ManagerUpdatePasswordForm();
        $model->manager = $manager;
        $model->username = $manager->username;
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function actionRole($id)
    {
        $model = new ManagerRoleForm();
        $model->id = $id;
        $model->roles = Yii::$app->services->rbacAuthAssignment->getRoleChildMap(AppEnum::BACKEND);
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        $model->role_ids = Yii::$app->services->rbacAuthAssignment->findRoleIdSByUserIdAndAppId($id, AppEnum::BACKEND);

        return $this->renderAjax($this->action->id, [
            'id' => $id,
            'model' => $model,
        ]);
    }
}
