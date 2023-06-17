<?php

namespace common\traits;

use Yii;
use yii\base\Model;
use yii\web\Response;
use yii\web\UnprocessableEntityHttpException;
use common\enums\AppEnum;

/**
 * trait BaseAction
 * @package common\traits
 * @author jianyan74 <751393839@qq.com>
 */
trait BaseAction
{
    /**
     * 默认分页
     *
     * @var int
     */
    protected $pageSize = 10;

    /**
     * 可跳转的方法 ID
     *
     * @var string[]
     */
    protected $referrerActionIds = ['edit', 'delete', 'destroy'];

    /**
     * 商户id
     *
     * @return int
     */
    protected function getMerchantId()
    {
        if (in_array(Yii::$app->id, [AppEnum::CONSOLE, AppEnum::BACKEND])) {
            return '';
        }

        return Yii::$app->services->merchant->getId();
    }

    /**
     * 店铺id
     *
     * @return int
     */
    protected function getStoreId()
    {
        if (in_array(Yii::$app->id, [AppEnum::CONSOLE, AppEnum::BACKEND])) {
            return '';
        }

        return Yii::$app->services->store->getId();
    }

    /**
     * @param Model $model
     * @throws UnprocessableEntityHttpException
     */
    protected function error(Model $model)
    {
        throw new UnprocessableEntityHttpException($this->getError($model));
    }

    /**
     * @param Model $model
     * @return string
     */
    protected function getError(Model $model)
    {
        return $this->analyErr($model->getFirstErrors());
    }

    /**
     * 解析错误
     *
     * @param $fistErrors
     * @return string
     */
    protected function analyErr($firstErrors)
    {
        return Yii::$app->services->base->analysisErr($firstErrors);
    }

    /**
     * @param $model \yii\db\ActiveRecord|Model
     * @throws \yii\base\ExitException
     */
    protected function activeFormValidate($model)
    {
        if (Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                Yii::$app->response->data = \yii\widgets\ActiveForm::validate($model);
                Yii::$app->end();
            }
        }
    }

    /**
     * 错误提示信息
     *
     * @param string $msgText 错误内容
     * @param string|array|Response $skipUrl 跳转链接
     * @param string $msgType 提示类型 [success/error/info/warning]
     * @return mixed
     */
    protected function message($msgText, $skipUrl, $msgType = null)
    {
        if (!$msgType || !in_array($msgType, ['success', 'error', 'info', 'warning'])) {
            $msgType = 'success';
        }

        Yii::$app->getSession()->setFlash($msgType, $msgText);

        return $skipUrl;
    }

    /**
     * 记录上一页地址
     *
     * @param $actionId
     */
    protected function setReferrer($actionId)
    {
        if (in_array($actionId, $this->referrerActionIds)) {
            $route = Yii::$app->controller->route;

            if (!Yii::$app->session->get($route)) {
                Yii::$app->session->set($route, Yii::$app->request->referrer);
            }
        }
    }

    /**
     * 跳转到之前的页面
     *
     * @return mixed
     */
    protected function referrer()
    {
        $key = Yii::$app->controller->route;
        $url = Yii::$app->session->get($key);
        Yii::$app->session->remove($key);
        if ($url) {
            return $this->redirect($url);
        }

        return $this->redirect(['index']);
    }
}
