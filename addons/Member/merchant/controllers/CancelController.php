<?php

namespace addons\Member\merchant\controllers;

use Yii;
use common\enums\AuditStatusEnum;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use common\models\member\Cancel;

/**
 * 会员注销
 *
 * Class CancelController
 * @package addons\Member\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class CancelController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Cancel
     */
    public $modelClass = Cancel::class;

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
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->with(['member'])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['merchant_id' => $this->getMerchantId()]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionRefuse($id)
    {
        /** @var Cancel $model */
        $model = Yii::$app->services->memberCancel->findById($id);
        if ($model->audit_status != StatusEnum::DISABLED) {
            return $this->message('申请已经被处理', $this->redirect(['index']), 'error');
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->audit_status == AuditStatusEnum::DISABLED) {
                $model->audit_status = AuditStatusEnum::DELETE;
                $model->save();

                return $this->message('拒绝成功', $this->redirect(Yii::$app->request->referrer));
            }

            return $this->message('拒绝失败', $this->redirect(Yii::$app->request->referrer));
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return array|mixed|string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionPass($id)
    {
        /** @var Cancel $model */
        $model = Yii::$app->services->memberCancel->findById($id);
        if ($model->audit_status != StatusEnum::DISABLED) {
            return $this->message('申请已经被处理', $this->redirect(['index']), 'error');
        }

        $model->audit_time = time();
        $model->audit_status = StatusEnum::ENABLED;
        $model->save();

        // 注销
        $member = Yii::$app->services->member->findById($model->member_id);
        $member->status = StatusEnum::DELETE;
        $member->save();

        Yii::$app->services->actionLog->create('memberCancel', '会员注销-' . $member->mobile);

        return $this->message('申请通过', $this->redirect(['index']));
    }
}
