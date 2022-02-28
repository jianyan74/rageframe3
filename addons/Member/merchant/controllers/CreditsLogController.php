<?php

namespace addons\Member\merchant\controllers;

use Yii;
use common\models\base\SearchModel;
use common\models\member\CreditsLog;
use common\enums\StatusEnum;
use common\enums\MemberTypeEnum;
use common\enums\CreditsLogTypeEnum;

/**
 * Class CreditsLogController
 * @package addons\Member\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class CreditsLogController extends BaseController
{
    /**
     * 消费日志
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionConsume()
    {
        list($dataProvider, $searchModel) = $this->getData(CreditsLogTypeEnum::CONSUME_MONEY);

        return $this->render('credit', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => '消费日志'
        ]);
    }

    /**
     * 余额日志
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionMoney()
    {
        list($dataProvider, $searchModel) = $this->getData(CreditsLogTypeEnum::USER_MONEY);

        return $this->render('credit', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => '余额日志'
        ]);
    }

    /**
     * 积分日志
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIntegral()
    {
        list($dataProvider, $searchModel) = $this->getData(CreditsLogTypeEnum::USER_INTEGRAL);

        return $this->render('credit', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => '积分日志'
        ]);
    }

    /**
     * 积分日志
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionGrowth()
    {
        list($dataProvider, $searchModel) = $this->getData(CreditsLogTypeEnum::USER_GROWTH);

        return $this->render('credit', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => '成长值日志'
        ]);
    }

    /**
     * @param $type
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    protected function getData($type)
    {
        $searchModel = new SearchModel([
            'model' => CreditsLog::class,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['type' => $type, 'member_type' => MemberTypeEnum::MEMBER])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->with(['member']);

        return [$dataProvider, $searchModel];
    }
}
