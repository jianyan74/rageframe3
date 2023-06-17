<?php

namespace addons\TinyBlog\frontend\controllers;

use Yii;
use yii\web\UnprocessableEntityHttpException;
use addons\TinyBlog\common\models\Single;

/**
 * Class SingleController
 * @package addons\TinyBlog\frontend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SingleController extends BaseController
{
    /**
     * @return string
     */
    public function actionView()
    {
        $id = Yii::$app->request->get('single_id');
        $model = Yii::$app->tinyBlogService->single->findById($id);
        if (empty($model)) {
            throw new UnprocessableEntityHttpException('找不到文章内容...');
        }

        Single::updateAllCounters(['view' => 1], ['id' => $model['id']]);

        return $this->render($this->action->id,[
            'model' => $model
        ]);
    }
}
