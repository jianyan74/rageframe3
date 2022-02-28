<?php

namespace backend\modules\common\controllers;

use Yii;
use common\models\common\AttachmentCate;
use backend\controllers\BaseController;
use common\traits\MerchantCurd;
use yii\data\ActiveDataProvider;

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
        $dataProvider = new ActiveDataProvider([
            'query' => $this->modelClass::find()
                ->orderBy('sort asc, created_at asc'),
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
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
        $request = Yii::$app->request;
        $id = $request->get('id', '');
        $model = $this->findModel($id);
        $model->pid = $request->get('pid', null) ?? $model->pid; // 父id

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax('ajax-edit', [
            'model' => $model,
        ]);
    }
}
