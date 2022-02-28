<?php

namespace api\modules\v1\controllers\common;

use yii\data\ActiveDataProvider;
use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\models\common\BankNumber;

/**
 * Class BankNumberController
 * @package api\modules\v1\controllers\common
 * @author jianyan74 <751393839@qq.com>
 */
class BankNumberController extends OnAuthController
{
    /**
     * @var BankNumber
     */
    public $modelClass = BankNumber::class;

    /**
     * 首页
     *
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => $this->modelClass::find()
                ->where(['status' => StatusEnum::ENABLED])
                ->orderBy('bank_number asc')
                ->asArray(),
            'pagination' => [
                'pageSize' => $this->pageSize,
                'validatePage' => false,// 超出分页不返回data
            ],
        ]);
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
        if (in_array($action, ['update', 'create', 'delete'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}
