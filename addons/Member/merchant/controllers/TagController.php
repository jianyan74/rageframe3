<?php

namespace addons\Member\merchant\controllers;

use Yii;
use common\traits\MerchantCurd;
use common\models\member\Tag;
use common\enums\StatusEnum;
use common\models\base\SearchModel;

/**
 * 会员标签
 *
 * Class TagController
 * @package addons\Member\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class TagController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Tag
     */
    public $modelClass = Tag::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'sort' => SORT_ASC,
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}
