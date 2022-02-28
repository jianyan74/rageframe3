<?php

namespace addons\RfDemo\merchant\controllers;

use addons\RfDemo\common\forms\CurdForm;
use Yii;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use addons\RfDemo\common\models\Curd;

/**
 * Class CurdController
 * @package addons\RfDemo\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class CurdController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Curd
     */
    public $modelClass = Curd::class;

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
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_ASC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->with(['cate']);

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
        $model = $this->findFormModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->referrer();
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'cates' => Yii::$app->rfDemoService->cate->findAll(),
        ]);
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     */
    protected function findFormModel($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || empty(($model = CurdForm::findOne(['id' => $id, 'merchant_id' => $this->getMerchantId()])))) {
            $model = new CurdForm();
            return $model->loadDefaultValues();
        }

        return $model;
    }
}
