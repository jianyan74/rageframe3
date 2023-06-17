<?php

namespace addons\TinyBlog\api\modules\v1\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use common\enums\StatusEnum;
use addons\TinyBlog\common\models\Article;
use api\controllers\OnAuthController;

/**
 * 文章接口
 *
 * Class ArticleController
 * @package addons\TinyBlog\api\modules\v1\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ArticleController extends OnAuthController
{
    /**
     * @var Article
     */
    public $modelClass = Article::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['index', 'view', 'list'];

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
                ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                ->select(['id', 'title', 'cover', 'author', 'cate_id', 'description', 'view'])
                ->orderBy('sort asc, id desc')
                ->with(['cate'])
                ->asArray(),
            'pagination' => [
                'pageSize' => $this->pageSize,
                'validatePage' => false,// 超出分页不返回data
            ],
        ]);
    }

    /**
     * 自定义装修可用
     *
     * 修改数据格式返回
     *
     * @return array|mixed
     */
    public function actionList()
    {
        $keyword = Yii::$app->request->get('keyword');

        $data = $this->modelClass::find()
            ->select(['id', 'merchant_id', 'title', 'description', 'cover', 'author', 'view'])
            ->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['like', 'title', $keyword])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->with(['merchant'])
            ->limit($pages->limit)
            ->asArray()
            ->all();

        return [
            'list' => $models,
            'pages' => [
                'totalCount' => $pages->totalCount,
                'pageSize' => $pages->pageSize,
            ]
        ];
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
        if (in_array($action, ['delete', 'create', 'update'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}
