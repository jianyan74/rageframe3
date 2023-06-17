<?php

namespace backend\modules\common\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\common\AttachmentCate;
use common\enums\AttachmentUploadTypeEnum;
use backend\controllers\BaseController;
use common\traits\MerchantCurd;

/**
 * Class AttachmentCateController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AttachmentCateController extends BaseController
{
    use MerchantCurd;

    /**
     * @var AttachmentCate
     */
    public $modelClass = AttachmentCate::class;

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $type = Yii::$app->request->get('type', AttachmentUploadTypeEnum::IMAGES);

        $dataProvider = new ActiveDataProvider([
            'query' => $this->modelClass::find()
                ->where(['type' => $type])
                ->andWhere([
                    'merchant_id' => Yii::$app->services->merchant->getNotNullId(),
                    'store_id' => Yii::$app->services->store->getNotNullId(),
                ])
                ->orderBy('sort asc, created_at asc'),
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'type' => $type,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id', '');
        $type = Yii::$app->request->get('type');
        $model = $this->findModel($id);
        $model->pid = Yii::$app->request->get('pid', null) ?? $model->pid; // 父id

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            $model->type = $type;

            return $model->save()
                ? $this->redirect(['index', 'type' => $type])
                : $this->message($this->getError($model), $this->redirect(['index', 'type' => $type]), 'error');
        }

        return $this->renderAjax('ajax-edit', [
            'model' => $model,
        ]);
    }
}
