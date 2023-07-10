<?php

namespace addons\TinyBlog\frontend\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\UnprocessableEntityHttpException;
use common\enums\StatusEnum;
use addons\TinyBlog\common\models\Article;

/**
 * Class IndexController
 * @package addons\TinyBlog\frontend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class IndexController extends BaseController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $keyword = Yii::$app->request->get('keyword');

        $data = Article::find()
            ->select(['id', 'merchant_id', 'cate_id', 'title', 'description', 'cover', 'author', 'view', 'created_at'])
            ->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['like', 'title', $keyword])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('sort asc, id desc')
            ->with(['cate'])
            ->limit($pages->limit)
            ->asArray()
            ->all();

        return $this->render($this->action->id, [
            'keyword' => $keyword,
            'models' => $models,
            'pages' => $pages,
            'adv' => Yii::$app->tinyBlogService->adv->newest(),
        ]);
    }

    /**
     * @return string
     */
    public function actionList()
    {
        $cateId = Yii::$app->request->get('cate_id');
        $cate = Yii::$app->tinyBlogService->cate->findById($cateId);
        $data = Article::find()
            ->select(['id', 'merchant_id', 'cate_id', 'title', 'description', 'cover', 'author', 'view', 'created_at'])
            ->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['cate_id' => $cateId])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('sort asc, id desc')
            ->with(['cate'])
            ->limit($pages->limit)
            ->asArray()
            ->all();

        if (empty($cate)) {
            throw new UnprocessableEntityHttpException('找不到分类内容...');
        }

        return $this->render($this->action->id, [
            'models' => $models,
            'cate' => $cate,
            'pages' => $pages,
        ]);
    }

    /**
     * @return string
     */
    public function actionTag()
    {
        $tag = Yii::$app->request->get('tag');
        $data = Article::find()
            ->select([
                Article::tableName().'.id',
                Article::tableName().'.merchant_id',
                'cate_id',
                Article::tableName().'.title',
                'description',
                'cover',
                'author',
                'view',
                Article::tableName().'.created_at',
            ])
            ->where([Article::tableName().'.status' => StatusEnum::ENABLED])
            ->with(['cate', 'merchant'])
            ->anyTagValues($tag)
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy(Article::tableName().'.sort asc, id desc')
            ->limit($pages->limit)
            ->asArray()
            ->all();

        if (empty($tag)) {
            throw new UnprocessableEntityHttpException('找不到标签...');
        }

        return $this->render($this->action->id, [
            'models' => $models,
            'tag' => $tag,
            'pages' => $pages,
        ]);
    }

    /**
     * @return string
     */
    public function actionView($id)
    {
        $model = Article::find()->where(['id' => $id, 'status' => StatusEnum::ENABLED])->one();
        if (empty($model)) {
            throw new UnprocessableEntityHttpException('找不到文章内容...');
        }

        $model->view += 1;
        Article::updateAllCounters(['view' => 1], ['id' => $model['id']]);

        return $this->render($this->action->id, [
            'model' => $model,
            'prev' => Yii::$app->tinyBlogService->article->getPrev($id),
            'next' => Yii::$app->tinyBlogService->article->getNext($id),
            'recommend' => Yii::$app->tinyBlogService->article->recommend(),
        ]);
    }
}
