<?php

namespace api\modules\v1\controllers\member;

use Yii;
use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\models\member\RechargeConfig;

/**
 * Class RechargeConfigController
 * @package api\modules\v1\controllers\member
 * @author jianyan74 <751393839@qq.com>
 */
class RechargeConfigController extends OnAuthController
{
    /**
     * @var RechargeConfig
     */
    public $modelClass = RechargeConfig::class;

    /**
     * @return array
     */
    public function actionIndex()
    {
        return $this->modelClass::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['merchant_id' => Yii::$app->services->merchant->getNotNullId()])
            ->orderBy('price asc')
            ->asArray()
            ->all();
    }

    /**
     * 权限验证
     *
     * @param string $action 当前的方法
     * @param null $model 当前的模型类
     * @param array $params $_GET变量
     * @throws \yii\web\BadRequestHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // 方法名称
        if (in_array($action, ['delete', 'create', 'update', 'view'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}
