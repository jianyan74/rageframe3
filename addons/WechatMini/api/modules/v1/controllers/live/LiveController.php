<?php

namespace addons\WechatMini\api\modules\v1\controllers\live;

use addons\WechatMini\common\enums\live\LiveStatusEnum;
use addons\WechatMini\common\models\live\Live;
use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class LiveController
 * @package addons\WechatMini\api\modules\v1\controllers\live
 * @author jianyan74 <751393839@qq.com>
 */
class LiveController extends OnAuthController
{
    /**
     * @var Live
     */
    public $modelClass = Live::class;

    /**
     * 不用进行登录验证的方法
     *
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['index'];

    /**
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {
        $is_recommend = Yii::$app->request->get('is_recommend');
        $live_status = Yii::$app->request->get('live_status');
        $where = [];
        switch ($live_status) {
            // 进行中
            case LiveStatusEnum::UNDERWAY :
                $where = [
                    'and',
                    ['<', 'start_time', time()],
                    ['>', 'end_time', time()]
                ];
                break;
            // 未开始
            case LiveStatusEnum::NOT_STARTED :
                $where = ['>', 'start_time', time()];
                break;
            // 已结束
            case LiveStatusEnum::END :
                $where = ['<', 'end_time', time()];
                break;
        }

        return new ActiveDataProvider([
            'query' => $this->modelClass::find()
                ->where(['status' => StatusEnum::ENABLED])
                ->andWhere(['in', 'live_status' , [LiveStatusEnum::UNDERWAY, LiveStatusEnum::NOT_STARTED, LiveStatusEnum::END]])
                ->andFilterWhere($where)
                ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                ->andFilterWhere(['is_recommend' => $is_recommend])
                ->cache(60)
                ->orderBy('is_stick asc, id desc')
                ->asArray(),
            'pagination' => [
                'pageSize' => $this->pageSize,
                'validatePage' => false,// 超出分页不返回data
            ],
        ]);
    }
}
