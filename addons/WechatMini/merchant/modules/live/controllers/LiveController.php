<?php

namespace addons\WechatMini\merchant\modules\live\controllers;

use Yii;
use common\enums\StatusEnum;
use common\helpers\ResultHelper;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;
use addons\WechatMini\merchant\controllers\BaseController;
use addons\WechatMini\common\models\live\Live;
use addons\WechatMini\common\models\live\GoodsMap;

/**
 * Class LiveController
 * @package addons\WechatMini\merchant\modules\live\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class LiveController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Live
     */
    public $modelClass = Live::class;

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'relations' => [],
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'roomid' => SORT_DESC,
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['merchant_id' => $this->getMerchantId()]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', null);
        /** @var Live $model */
        $model = $this->findModel($id);
        $oldModel = ArrayHelper::toArray($model);
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->validate()) {
                return ResultHelper::json(422, $this->getError($model));
            }

            if (empty($model->anchor_wechat)) {
                return ResultHelper::json(422, '请填写主播微信号');
            }

            try {
                if ($oldModel['cover_img'] != $model->cover_img || empty($model->cover_media)) {
                    $coverImg = Yii::$app->wechat->miniProgram->media->uploadImage(StringHelper::getLocalFilePath($model->cover_img));
                    $model->cover_media = $coverImg['media_id'] ?? '';
                }

                if ($oldModel['share_img'] != $model->share_img || empty($model->share_media)) {
                    $shareImg = Yii::$app->wechat->miniProgram->media->uploadImage(StringHelper::getLocalFilePath($model->share_img));
                    $model->share_media = $shareImg['media_id'] ?? '';
                }

                if ($oldModel['feeds_img'] != $model->cover_img || empty($model->feeds_media)) {
                    $feedsImg = Yii::$app->wechat->miniProgram->media->uploadImage(StringHelper::getLocalFilePath($model->feeds_img));
                    $model->feeds_media = $feedsImg['media_id'] ?? '';
                }

                $data = [
                    'name' => $model->name,
                    'coverImg' => $model->cover_media,
                    'startTime' => StringHelper::dateToInt($model->start_time),
                    'endTime' => StringHelper::dateToInt($model->end_time),
                    'anchorName' => $model->anchor_name,
                    'anchorWechat' => $model->anchor_wechat,
                    'shareImg' => $model->share_media,
                    'feedsImg' => $model->feeds_media,
                    'isFeedsPublic' => $model->is_feeds_public,
                    'type' => $model->live_type,
                    'closeLike' => $model->close_like,
                    'closeGoods' => $model->close_goods,
                    'closeComment' => $model->close_comment,
                    'closeReplay' => $model->close_replay,
                    'closeShare' => $model->close_share,
                    'closeKf' => $model->close_kf,
                ];

                if (!empty($model->roomid)) {
                    $data['id'] = $model->roomid;
                    $result = Yii::$app->wechat->miniProgram->broadcast->updateLiveRoom($data);
                    Yii::$app->services->base->getWechatError($result);
                    $model->share_path = Yii::$app->wechat->miniProgram->broadcast->getShareQrcode(['roomId' => $model->roomid]);
                    // 获取推流地址
                    if (empty($model->push_addr)) {
                        $pushUrl = Yii::$app->wechat->miniProgram->broadcast->getPushUrl(['roomId' => $model->roomid]);
                        $model->push_addr = $pushUrl['pushAddr'] ?? '';
                    }
                } else {
                    $result = Yii::$app->wechat->miniProgram->broadcast->createLiveRoom($data);
                    Yii::$app->services->base->getWechatError($result);
                    $model->roomid = $result['roomId'];
                    $model->qrcode_url = $result['qrcode_url'] ?? '';

                    // 获取推流地址
                    $pushUrl = Yii::$app->wechat->miniProgram->broadcast->getPushUrl(['roomId' => $model->roomid]);
                    $model->push_addr = $pushUrl['pushAddr'] ?? '';

                    // 获取直播间分享二维码
                    $model->share_path = Yii::$app->wechat->miniProgram->broadcast->getShareQrcode(['roomId' => $model->roomid]);
                }

                $model->save();

                return ResultHelper::json(200, 'ok');
            } catch (\Exception $e) {
                return ResultHelper::json(422, $e->getMessage());
            }
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'referrer' => Yii::$app->request->referrer,
        ]);
    }

    /**
     * 伪删除
     *
     * @param $id
     * @return mixed
     */
    public function actionDestroy($id)
    {
        if (!($model = $this->modelClass::findOne($id))) {
            return $this->message("找不到数据", $this->redirect(Yii::$app->request->referrer), 'error');
        }

        $model->status = StatusEnum::DELETE;
        if ($model->save()) {
            Yii::$app->wechat->miniProgram->broadcast->deleteLiveRoom(['roomId' => $model->roomid]);

            return $this->message("删除成功", $this->redirect(Yii::$app->request->referrer));
        }

        return $this->message("删除失败", $this->redirect(Yii::$app->request->referrer), 'error');
    }

    /**
     * 同步
     *
     * @param int $offset
     * @param int $count
     * @param int $clear
     * @return array|mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function actionSync($offset = 0, $count = 20, $clear = 0)
    {
        if ($clear == StatusEnum::ENABLED) {
            Live::updateAll(['status' => StatusEnum::DELETE], ['merchant_id' => Yii::$app->services->merchant->getNotNullId()]);
            GoodsMap::deleteAll(['merchant_id' => Yii::$app->services->merchant->getNotNullId()]);
        }

        try {
            $res = Yii::$app->wechatMiniService->live->sync($offset, $count);
            if (is_array($res)) {
                return ResultHelper::json(200, '同步成功', $res);
            }

            return ResultHelper::json(201, '同步完成');
        } catch (\Exception $e) {
            return ResultHelper::json(422, $e->getMessage());
        }
    }
}
