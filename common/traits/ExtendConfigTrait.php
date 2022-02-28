<?php

namespace common\traits;

use Yii;
use common\enums\StatusEnum;
use common\enums\ExtendConfigNameEnum;
use common\enums\ExtendConfigTypeEnum;
use common\models\base\SearchModel;
use common\models\extend\Config;
use common\helpers\ArrayHelper;
use common\helpers\ResultHelper;

/**
 * 扩展配置
 *
 * Trait ExtendConfigTrait
 * @package common\traits
 */
trait ExtendConfigTrait
{
    /**
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => Config::class,
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
            ->andWhere(['type' => $this->type])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['merchant_id' => Yii::$app->services->merchant->getNotNullId()]);

        return $this->render('@backend/modules/extend/views/config/index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'nameMap' => ExtendConfigNameEnum::getGroupValue($this->type),
            'title' => ExtendConfigTypeEnum::getValue($this->type)
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
        $name = Yii::$app->request->get('name');
        $model = $this->findModel($id);
        $model->type = $this->type;
        $model->name = $name;
        $dataModel = ExtendConfigNameEnum::getModelValue($name);
        $dataModel->attributes = $model->data;

        if (
            $dataModel->load(Yii::$app->request->post()) &&
            $model->load(Yii::$app->request->post()) &&
            $model->validate()
        ) {
            $model->data = ArrayHelper::toArray($dataModel);
            $model->save();

            return $this->referrer();
        }

        return $this->render('@backend/modules/extend/views/config/edit', [
            'model' => $model,
            'dataModel' => $dataModel,
        ]);
    }

    /**
     * 删除
     *
     * @param $id
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            return $this->message("删除成功", $this->redirect(Yii::$app->request->referrer));
        }

        return $this->message("删除失败", $this->redirect(Yii::$app->request->referrer), 'error');
    }

    /**
     * 伪删除
     *
     * @param $id
     * @return mixed
     */
    public function actionDestroy($id)
    {
        if (!($model = $this->findModel($id))) {
            return $this->message("找不到数据", $this->redirect(Yii::$app->request->referrer), 'error');
        }

        $model->status = StatusEnum::DELETE;
        if ($model->save()) {
            return $this->message("删除成功", $this->redirect(Yii::$app->request->referrer));
        }

        return $this->message("删除失败", $this->redirect(Yii::$app->request->referrer), 'error');
    }

    /**
     * ajax更新排序/状态
     *
     * @param $id
     * @return array
     */
    public function actionAjaxUpdate($id)
    {
        if (!($model = Config::findOne($id))) {
            return ResultHelper::json(404, '找不到数据');
        }

        $model->attributes = ArrayHelper::filter(Yii::$app->request->get(), ['sort', 'status']);
        if (!$model->save()) {
            return ResultHelper::json(422, $this->getError($model));
        }

        return ResultHelper::json(200, '修改成功');
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
        if (empty($id) || empty(($model = Config::findOne(['id' => $id, 'merchant_id' => $this->getMerchantId()])))) {
            $model = new Config();
            return $model->loadDefaultValues();
        }

        return $model;
    }
}
