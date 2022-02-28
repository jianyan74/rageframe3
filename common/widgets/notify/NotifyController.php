<?php

namespace common\widgets\notify;

use Yii;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\common\NotifyMember;
use common\helpers\ResultHelper;
use backend\controllers\BaseController;

/**
 * Class NotifyController
 * @package common\widgets\notify
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyController extends BaseController
{
    protected $view = '@common/widgets/notify/views/';

    /**
     * 提醒
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRemind()
    {
        $type = Yii::$app->request->get('type');

        $searchModel = new SearchModel([
            'model' => NotifyMember::class,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->with(['notify'])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['merchant_id' => Yii::$app->user->identity->merchant_id])
            ->andFilterWhere(['type' => $type]);

        return $this->render($this->view . $this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'type' => $type,
        ]);
    }

    /**
     * 公告详情
     *
     * @param $id
     * @return mixed|string
     */
    public function actionAnnounceView($id)
    {
        if (empty($id) || empty(($model = NotifyMember::find()->where([
                'id' => $id,
                'status' => StatusEnum::ENABLED
            ])->with(['notify'])->one()))) {
            return $this->message('找不到该公告', $this->redirect(['index']), 'error');
        }

        // 设置公告为已读
        Yii::$app->services->notifyMember->readByNotifyId(Yii::$app->user->id, Yii::$app->user->identity->merchant_id, [$model->notify_id]);

        return $this->render($this->view . $this->action->id, [
            'model' => Yii::$app->services->notifyAnnounce->findById($model->notify->target_id),
        ]);
    }

    /**
     * @return mixed
     */
    public function actionRead()
    {
        $ids = Yii::$app->request->post('ids');
        if (empty($ids)) {
            return ResultHelper::json(422, '请至少选中一项');
        }

        Yii::$app->services->notifyMember->readById(Yii::$app->user->id, Yii::$app->user->identity->merchant_id, $ids);

        return ResultHelper::json(200, 'ok');
    }

    /**
     * @return mixed
     */
    public function actionDeleteAll()
    {
        $ids = Yii::$app->request->post('ids');
        if (empty($ids)) {
            return ResultHelper::json(422, '请至少选中一项');
        }

        Yii::$app->services->notifyMember->deleteById(Yii::$app->user->identity->merchant_id, $ids);

        return ResultHelper::json(200, 'ok');
    }

    /**
     * @return mixed
     */
    public function actionReadAll()
    {
        Yii::$app->services->notifyMember->readAll(Yii::$app->user->id, Yii::$app->user->identity->merchant_id);

        return $this->message('全部设为已读成功', $this->redirect(['remind']));
    }
}