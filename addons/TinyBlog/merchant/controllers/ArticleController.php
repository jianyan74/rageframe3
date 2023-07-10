<?php

namespace addons\TinyBlog\merchant\controllers;

use Yii;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use common\enums\StatusEnum;
use addons\TinyBlog\common\models\Article;
use addons\TinyBlog\common\enums\ArticlePositionEnum;

/**
 * Class ArticleController
 * @package addons\TinyBlog\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ArticleController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Article
     */
    public $modelClass = Article::class;

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
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['merchant_id' => $this->getMerchantId()]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 回收站
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRecycle()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'sort' => SORT_ASC,
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['status' => StatusEnum::DISABLED])
            ->andWhere(['merchant_id' => $this->getMerchantId()]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 还原
     *
     * @param $id
     * @return mixed
     */
    public function actionShow($id)
    {
        $model = $this->findModel($id);
        $model->status = StatusEnum::ENABLED;
        if ($model->save()) {
            return $this->message("还原成功", $this->redirect(['recycle']));
        }

        return $this->message("还原失败", $this->redirect(['recycle']), 'error');
    }

    /**
     * 删除
     *
     * @param $id
     * @return mixed
     */
    public function actionHide($id)
    {
        $model = $this->findModel($id);
        $model->status = StatusEnum::DISABLED;
        if ($model->save()) {
            return $this->message("删除成功", $this->redirect(['index']));
        }

        return $this->message("删除失败", $this->redirect(['index']), 'error');
    }

    /**
     * 编辑/创建
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', null);
        $model = $this->findModel($id);
        // 设置选中标签
        $model->tagValues = $model->getTagValues(true);
        // 推荐位
        $positionExplain = ArticlePositionEnum::getMap();
        $keys = [];
        foreach ($positionExplain as $key => $value) {
            if (ArticlePositionEnum::checkPosition($key, $model->position)) {
                $keys[] = $key;
            }
        }
        $model->position = $keys;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->referrer();
            }
        }

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }
}
