<?php

namespace addons\RfDemo\merchant\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\traits\MerchantCurd;
use addons\RfDemo\common\models\Cate;

/**
 * Class CateTreeGridController
 * @package addons\RfDemo\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class CateTreeGridController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Cate
     */
    public $modelClass = Cate::class;

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $this->modelClass::find()
                ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                ->orderBy('sort asc, id desc'),
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
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
        $model = $this->findModel($id);
        $model->pid = Yii::$app->request->get('pid', null) ?? $model->pid; // 父id

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'dropDownList' => Yii::$app->rfDemoService->cate->getDropDownForEdit($id),
        ]);
    }
}
