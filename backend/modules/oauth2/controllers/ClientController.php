<?php

namespace backend\modules\oauth2\controllers;

use Yii;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use common\models\oauth2\Client;
use common\enums\StatusEnum;
use common\helpers\StringHelper;
use backend\controllers\BaseController;

/**
 * 客户端
 *
 * Class ClientController
 * @package backend\modules\member\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ClientController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Client
     */
    public $modelClass = Client::class;

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
            'partialMatchAttributes' => ['title', 'client_id'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['merchant_id' => 0]);

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
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id');
        /** @var Client $model */
        $model = $this->findModel($id);

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        if ($model->isNewRecord) {
            $model->client_id = StringHelper::random(15);
            $model->client_secret = StringHelper::random(30);
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }
}
