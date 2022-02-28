<?php

namespace backend\modules\common\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\enums\AppEnum;
use common\traits\Curd;
use common\models\common\Menu;
use common\helpers\ResultHelper;
use backend\controllers\BaseController;

/**
 * Class MenuController
 * @package backend\modules\common\controllers
 */
class MenuController extends BaseController
{
    use Curd;

    /**
     * @var \yii\db\ActiveRecord
     */
    public $modelClass = Menu::class;

    /**
     * @return string
     */
    public function actionIndex()
    {
        $cateId = Yii::$app->request->get('cate_id', Yii::$app->services->menuCate->findFirstId(AppEnum::BACKEND));

        $query = $this->modelClass::find()
            ->orderBy('sort asc, id asc')
            ->filterWhere(['cate_id' => $cateId])
            ->andWhere(['app_id' => AppEnum::BACKEND]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'cates' => Yii::$app->services->menuCate->findDefault(AppEnum::BACKEND),
            'cateId' => $cateId,
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
        $model = $this->findModel($id);
        $model->pid = Yii::$app->request->get('pid', null) ?? $model->pid; // 父id
        $model->cate_id = Yii::$app->request->get('cate_id', null) ?? $model->cate_id; // 分类id
        $model->app_id = AppEnum::BACKEND;

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->save()) {
                return ResultHelper::json(422, $this->getError($model));
            }

            return ResultHelper::json(200, '保存成功');
        }

        $menuCate = Yii::$app->services->menuCate->findById($model->cate_id);
        $this->layout = '@backend/views/layouts/blank';

        return $this->render('edit', [
            'model' => $model,
            'cates' => Yii::$app->services->menuCate->getDefaultMap(AppEnum::BACKEND),
            'menuDropDownList' => Yii::$app->services->menu->getDropDown($menuCate, AppEnum::BACKEND, $id),
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
        if (($model = $this->findModel($id))->delete()) {
            return $this->message("删除成功", $this->redirect(['index', 'cate_id' => $model->cate_id]));
        }

        return $this->message("删除失败", $this->redirect(['index', 'cate_id' => $model->cate_id]), 'error');
    }
}
