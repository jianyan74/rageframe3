<?php

namespace addons\Wechat\merchant\controllers;

use Yii;
use common\helpers\ArrayHelper;
use addons\Wechat\merchant\forms\HistoryForm;

/**
 * Class SettingController
 * @package addons\Wechat\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SettingController extends BaseController
{
    /**
     * @return mixed|string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionSpecialMessage()
    {
        if (Yii::$app->request->isPost) {
            try {
                Yii::$app->services->addonsConfig->setConfig([
                    'special' => Yii::$app->request->post('setting')
                ]);

                return $this->message('修改成功', $this->redirect(['special-message']));
            } catch (\Exception $e) {
                return $this->message($e->getMessage(), $this->redirect(['special-message']), 'error');
            }
        }

        return $this->render('special-message', [
            'list' => Yii::$app->wechatService->config->specialConfig(),
        ]);
    }
}
