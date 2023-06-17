<?php

namespace backend\modules\common\controllers;

use Yii;
use common\traits\MerchantCurd;
use common\models\base\SearchModel;
use common\models\common\Attachment;
use common\enums\StatusEnum;
use common\enums\AttachmentUploadTypeEnum;
use backend\controllers\BaseController;

/**
 * Class AttachmentController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AttachmentController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Attachment
     */
    public $modelClass = Attachment::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $type = Yii::$app->request->get('type', AttachmentUploadTypeEnum::IMAGES);

        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['upload_type' => $type])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'cateMap' => Yii::$app->services->attachmentCate->getMap($type),
            'type' => $type,
        ]);
    }

    /**
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->get('id');
        $type = Yii::$app->request->get('type');
        $model = $this->findModel($id);

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(['index', 'type' => $type])
                : $this->message($this->getError($model), $this->redirect(['index', 'type' => $type]), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'cateMap' => Yii::$app->services->attachmentCate->getMap($type),
        ]);
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     */
    protected function findModel($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (
            empty($id) || empty(
                ($model = $this->modelClass::find()
                    ->where(['id' => $id])
                    ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                    ->one()))
        ) {
            $model = new $this->modelClass;
            return $model->loadDefaultValues();
        }

        return $model;
    }
}
