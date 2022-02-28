<?php

namespace addons\Wechat\merchant\controllers;

use Yii;
use yii\web\Response;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use addons\Wechat\common\models\Qrcode;
use addons\Wechat\common\enums\QrcodeModelTypeEnum;

/**
 * 微信二维码管理
 *
 * Class QrcodeController
 * @package addons\Wechat\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class QrcodeController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Qrcode
     */
    public $modelClass = Qrcode::class;

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
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['addon_name' => Yii::$app->params['addon']['name']])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * @return mixed|string|Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id');
        /** @var Qrcode $model */
        $model = $this->findModel($id);

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            $model->isNewRecord && $model = Yii::$app->wechatService->qrcode->syncCreate($model);
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 删除全部过期的二维码
     *
     * @return mixed
     */
    public function actionDeleteAll()
    {
        if (Qrcode::deleteAll([
            'and',
            ['model_type' => QrcodeModelTypeEnum::TEM],
            ['<', 'end_time', time()],
            ['merchant_id' => $this->getMerchantId()]
        ])) {
            return $this->message("删除成功", $this->redirect(['index']));
        }

        return $this->message("删除失败", $this->redirect(['index']), 'error');
    }

    /**
     * 下载二维码
     */
    public function actionDown()
    {
        $id = Yii::$app->request->get('id');
        $model = Qrcode::findOne($id);
        $url = Yii::$app->wechat->app->qrcode->url($model['ticket']);

        header("Cache-control:private");
        header('content-type:image/jpeg');
        header('content-disposition: attachment;filename="' . $model['name'] . '_' . time() . '.jpg"');
        readfile($url);
    }

    /**
     * 二维码转换
     *
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionQr()
    {
        $getUrl = Yii::$app->request->get('shortUrl', Yii::$app->request->hostInfo);

        $qr = Yii::$app->get('qr');
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', $qr->getContentType());

        return $qr->setText($getUrl)
            ->setSize(150)
            ->setMargin(7)
            ->writeString();
    }
}