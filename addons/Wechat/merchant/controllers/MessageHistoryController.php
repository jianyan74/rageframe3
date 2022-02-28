<?php

namespace addons\Wechat\merchant\controllers;

use Yii;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use addons\Wechat\common\models\MessageHistory;
use addons\Wechat\common\enums\RuleModuleEnum;

/**
 * 微信历史消息
 *
 * Class MessageHistoryController
 * @package addons\Wechat\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MessageHistoryController extends BaseController
{
    use MerchantCurd;

    /**
     * @var messageHistory
     */
    public $modelClass = MessageHistory::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $startTime = Yii::$app->request->get('start_time', date('Y-m-d', strtotime("-10 day")));
        $endTime = Yii::$app->request->get('end_time', date('Y-m-d', strtotime("+1 day")));

        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['message'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andFilterWhere(['between', 'created_at', !empty($startTime) ? strtotime($startTime) : '', !empty($endTime) ? strtotime($endTime) : ''])
            ->with(['fans', 'auth', 'rule']);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'moduleExplain' => RuleModuleEnum::getMap(),
            'startTime' => $startTime,
            'endTime' => $endTime,
        ]);
    }
}
