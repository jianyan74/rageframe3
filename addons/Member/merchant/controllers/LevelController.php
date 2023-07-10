<?php

namespace addons\Member\merchant\controllers;

use Yii;
use common\enums\MemberTypeEnum;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use common\enums\StatusEnum;
use common\models\member\Level;

/**
 * 会员等级管理
 *
 * Class LevelController
 * @author Maomao
 * @package addons\Member\merchant\controllers
 */
class LevelController extends BaseController
{
    use MerchantCurd;

    /**
     * @var \yii\db\ActiveRecord
     */
    public $modelClass = Level::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $merchant_id = Yii::$app->services->merchant->getNotNullId();
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'level' => SORT_ASC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['merchant_id' => $merchant_id]);

        $levelConfig = Yii::$app->services->memberLevelConfig->one($merchant_id);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'memberLevelUpgradeType' => $levelConfig->upgrade_type
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
        $model = $this->findModel($id);
        if ($hasLevel = Yii::$app->services->member->hasLevel($model->level, MemberTypeEnum::MEMBER, $model->merchant_id)) {
            return $this->message("已经在使用中，无法删除", $this->redirect(['index']), 'error');
        }

        if ($model->delete()) {
            return $this->message("删除成功", $this->redirect(['index']));
        }

        return $this->message("删除失败", $this->redirect(['index']), 'error');
    }
}
