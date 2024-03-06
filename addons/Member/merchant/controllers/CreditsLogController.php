<?php

namespace addons\Member\merchant\controllers;

use Yii;
use common\models\base\SearchModel;
use common\models\member\CreditsLog;
use common\enums\StatusEnum;
use common\enums\MemberTypeEnum;
use common\enums\CreditsLogTypeEnum;
use common\helpers\ExcelHelper;

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
        list($dataProvider, $searchModel, $startTime, $endTime) = $this->getData(CreditsLogTypeEnum::CONSUME_MONEY);

        return $this->render('credit', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'action' => $this->action->id,
            'type' => CreditsLogTypeEnum::CONSUME_MONEY,
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
        list($dataProvider, $searchModel, $startTime, $endTime) = $this->getData(CreditsLogTypeEnum::USER_MONEY);

        return $this->render('credit', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'action' => $this->action->id,
            'type' => CreditsLogTypeEnum::USER_MONEY,
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
        list($dataProvider, $searchModel, $startTime, $endTime) = $this->getData(CreditsLogTypeEnum::USER_INTEGRAL);

        return $this->render('credit', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'action' => $this->action->id,
            'type' => CreditsLogTypeEnum::USER_INTEGRAL,
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
        list($dataProvider, $searchModel, $startTime, $endTime) = $this->getData(CreditsLogTypeEnum::USER_GROWTH);

        return $this->render('credit', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'action' => $this->action->id,
            'type' => CreditsLogTypeEnum::USER_GROWTH,
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
        $startTime = Yii::$app->request->get('start_time');
        $endTime = Yii::$app->request->get('end_time');

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
            ->andFilterWhere(['between', 'created_at', !empty($startTime) ? strtotime($startTime) : '', !empty($endTime) ? strtotime($endTime) : ''])
            ->with(['member']);

        return [$dataProvider, $searchModel, $startTime, $endTime];
    }

    /**
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function actionExport()
    {
        $data = Yii::$app->request->get('SearchModel');
        $startTime = Yii::$app->request->get('start_time');
        $endTime = Yii::$app->request->get('end_time');
        $type = Yii::$app->request->get('type');

        $list = CreditsLog::find()
            ->where(['type' => $type, 'status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->andFilterWhere(['member_id' => $data['member_id']])
            ->andFilterWhere(['num' => $data['num']])
            ->andFilterWhere(['new_num' => $data['new_num']])
            ->andFilterWhere(['like', 'remark', $data['remark']])
            ->andFilterWhere(['between', 'created_at', !empty($startTime) ? strtotime($startTime) : '', !empty($endTime) ? strtotime($endTime) : ''])
            ->with(['baseMember'])
            ->orderBy('id desc')
            ->asArray()
            ->all();

        $header = [
            ['ID', 'id'],
            ['会员ID', 'baseMember.id'],
            ['会员', 'baseMember.nickname'],
            ['变动数量', 'num'],
            ['变动后数量', 'new_num'],
            ['备注', 'remark'],
            ['创建时间', 'created_at', 'date', 'Y-m-d H:i:s'],
        ];

        return ExcelHelper::exportData($list, $header, CreditsLogTypeEnum::getValue($type) . '_' . date('YmdHis'));
    }
}
