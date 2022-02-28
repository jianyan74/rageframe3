<?php

namespace backend\modules\common\controllers;

use Yii;
use common\enums\AppEnum;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\common\MenuCate;
use common\traits\Curd;
use common\enums\AddonTypeEnum;
use backend\controllers\BaseController;

/**
 * Class MenuCateController
 * @package backend\modules\common\controllers
 */
class MenuCateController extends BaseController
{
    use Curd;

    /**
     * @var MenuCate
     */
    public $modelClass = MenuCate::class;

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
                'sort' => SORT_ASC,
                'id' => SORT_ASC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['in', 'addon_location', ['', AddonTypeEnum::DEFAULT]])
            ->andWhere(['app_id' => AppEnum::BACKEND]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'cates' => Yii::$app->services->menuCate->findDefault(AppEnum::BACKEND),
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
        if (empty($id) || empty(($model = $this->modelClass::findOne($id)))) {
            $model = new $this->modelClass;
            $model = $model->loadDefaultValues();
            $model->app_id = AppEnum::BACKEND;
        }

        return $model;
    }
}
