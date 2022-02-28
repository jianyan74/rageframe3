<?php

namespace addons\Wechat\merchant\controllers;

use Yii;
use common\helpers\ArrayHelper;
use addons\Wechat\merchant\forms\ReplyDefaultForm;

/**
 * 默认回复控制器
 *
 * Class ReplyDefaultController
 * @package addons\Wechat\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ReplyDefaultController extends BaseController
{
    /**
     * 首页
     *
     * @return mixed|string
     */
    public function actionIndex()
    {
        $model = new ReplyDefaultForm();
        $model->attributes = Yii::$app->services->addonsConfig->getConfig();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->services->addonsConfig->setConfig(ArrayHelper::toArray($model));

            return $this->message('保存成功', $this->redirect(['index']));
        }

        // 关键字
        $keyword = Yii::$app->wechatService->ruleKeyword->getList();
        $keyword = ArrayHelper::map($keyword, 'content', 'content');
        $keyword = ArrayHelper::merge([' ' => '不触发关键字'], $keyword);

        return $this->render('index', [
            'model' => $model,
            'keyword' => $keyword
        ]);
    }
}