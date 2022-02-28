<?php

namespace addons\RfDemo\merchant\controllers;

use Yii;
use common\helpers\ResultHelper;
use common\traits\MerchantCurd;
use addons\RfDemo\common\models\Cate;

/**
 * Class CateJsTreeController
 * @package addons\RfDemo\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class CateJsTreeController extends BaseController
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
        return $this->render('index', [
            'data' => Yii::$app->rfDemoService->cate->findAll()
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
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->save()) {
                return ResultHelper::json(422, $this->getError($model));
            }

            return ResultHelper::json(200, '修改成功', $model);
        }

        $map = ['0' => '顶级'];
        if ($model->pid && $parent = Yii::$app->rfDemoService->cate->findById($model->pid)) {
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
