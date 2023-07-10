<?php

namespace addons\Member\merchant\controllers;

use Yii;
use common\traits\MerchantCurd;
use common\models\member\LevelConfig;

/**
 * Class LevelConfigController
 * @package addons\Member\merchant\controllers
 */
class LevelConfigController extends BaseController
{
    use MerchantCurd;

    /**
     * @var LevelConfig
     */
    public $modelClass = LevelConfig::class;

    /**
     * 编辑/创建
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $id = Yii::$app->services->merchant->getNotNullId();
        $model = Yii::$app->services->memberLevelConfig->one($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->message('保存成功', $this->redirect(['edit']));
        }

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }
}
