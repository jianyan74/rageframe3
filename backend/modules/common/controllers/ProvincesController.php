<?php

namespace backend\modules\common\controllers;

use Yii;
use yii\web\Response;
use common\helpers\ResultHelper;
use common\models\common\Provinces;
use common\traits\Curd;
use backend\controllers\BaseController;

/**
 * Class ProvincesController
 * @package backend\modules\common\controllers
 */
class ProvincesController extends BaseController
{
    use Curd;

    /**
     * @var Provinces
     */
    public $modelClass = Provinces::class;

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', []);
    }

    /**
     * @param $pid
     * @return array
     */
    public function actionList($pid)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = Yii::$app->services->provinces->getCityByPid($pid);
        $jsTreeData = [];
        foreach ($data as $datum) {
            $data = [
                'id' => $datum['id'],
                'parent' => !empty($datum['pid']) ? $datum['pid'] : '#',
                'text' => trim($datum['title']),
                'icon' => 'fa fa-folder',
                'children' => true
            ];

            $jsTreeData[] = $data;
        }

        return $jsTreeData;
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
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->save()) {
                return ResultHelper::json(422, $this->getError($model));
            }

            return ResultHelper::json(200, '修改成功', $model);
        }

        $map = ['0' => '顶级'];
        if ($model->pid && $parent = Yii::$app->services->provinces->findById($model->pid)) {
            $map = [$parent['id'] => $parent['title']];
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'map' => $map,
        ]);
    }

    /**
     * 移动
     *
     * @param $id
     * @param int $pid
     */
    public function actionMove($id, $pid = 0)
    {
        $model = $this->findModel($id);
        $model->pid = $pid;
        $model->save();
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
            return ResultHelper::json(200, '删除成功');
        }

        return ResultHelper::json(422, '删除失败');
    }
}
