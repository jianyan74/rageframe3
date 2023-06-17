<?php

namespace addons\Member\merchant\controllers;

use Yii;
use common\enums\MemberTypeEnum;
use common\enums\StatusEnum;
use common\traits\MerchantCurd;
use common\forms\MemberForm as Member;
use common\models\base\SearchModel;

/**
 * 黑名单
 *
 * Class BlacklistController
 * @package addons\Member\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class BlacklistController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Member
     */
    public $modelClass = Member::class;

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
            'partialMatchAttributes' => ['realname', 'mobile'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere([
                'type' => MemberTypeEnum::MEMBER,
                'status' => StatusEnum::DISABLED
            ])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->with(['account', 'memberLevel', 'tag']);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'levelMap' => Yii::$app->services->memberLevel->getMap(),
        ]);
    }

    /**
     * 黑名单
     *
     * @param $id
     * @return mixed
     */
    public function actionBlacklist($id)
    {
        if (!($model = $this->modelClass::findOne($id))) {
            return $this->message("找不到数据", $this->redirect(Yii::$app->request->referrer), 'error');
        }

        $model->status = StatusEnum::ENABLED;
        if ($model->save()) {
            return $this->message("移出黑名单成功", $this->redirect(Yii::$app->request->referrer));
        }

        return $this->message("移出黑名单失败", $this->redirect(Yii::$app->request->referrer), 'error');
    }
}
