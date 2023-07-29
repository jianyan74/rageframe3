<?php

namespace addons\WechatMini\services\live;

use addons\WechatMini\common\enums\live\LiveStatusEnum;
use addons\WechatMini\common\models\live\GoodsMap;
use addons\WechatMini\common\models\live\Live;
use common\components\Service;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use Yii;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class LiveService
 * @package addons\WechatMini\services\live
 * @author jianyan74 <751393839@qq.com>
 */
class LiveService extends Service
{
    /**
     * @param $room_ids
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByCustom()
    {
        $data = Live::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andWhere(['in', 'live_status' , [LiveStatusEnum::UNDERWAY, LiveStatusEnum::NOT_STARTED, LiveStatusEnum::END]])
            ->andFilterWhere(['is_recommend' => StatusEnum::ENABLED])
            ->asArray()
            ->one();

        if (empty($data)) {
            return [];
        }

        // 修改状态为直播中
        if (
            LiveStatusEnum::NOT_STARTED == $data['live_status'] &&
            time() >= $data['start_time'] &&
            time() <= $data['end_time']
        ) {
            $data['live_status'] = LiveStatusEnum::UNDERWAY;
            Live::updateAll(['live_status' => LiveStatusEnum::UNDERWAY], ['id' => $data['id']]);
        }

        // 修改状态为已结束
        if (
            LiveStatusEnum::UNDERWAY == $data['live_status'] &&
            time() > $data['end_time']
        ) {
            $data['live_status'] = LiveStatusEnum::END;
            Live::updateAll(['live_status' => LiveStatusEnum::END], ['id' => $data['id']]);
        }

        if (empty($data)) {
            if (!YII_DEBUG) {
                return [];
            }

            $data = new Live();
            $data = $data->loadDefaultValues();
        }

        return ArrayHelper::toArray($data);
    }

    /**
     * 同步房间
     *
     * @param $offset
     * @param $count
     * @return array|bool
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function sync($offset, $count)
    {
        $lists = Yii::$app->wechat->miniProgram->broadcast->getRooms($offset, $count);
        if (empty($lists)) {
            return true;
        }

        $total = $lists['total'];
        // 房间列表
        $roomInfo = $lists['room_info'];
        $goods = [];
        foreach ($roomInfo as $vo) {
            if (empty($live = $this->findRoomId($vo['roomid']))) {
                $live = new Live();
                $live = $live->loadDefaultValues();
            }

            $live->attributes = $vo;
            $coverImg = Yii::$app->services->extendUpload->downloadByUrl($live->cover_img);
            $live->cover_img = $coverImg['url'] ?? '';
            $shareImg = Yii::$app->services->extendUpload->downloadByUrl($live->share_img);
            $live->share_img = $shareImg['url'] ?? '';
            $feedsImg = Yii::$app->services->extendUpload->downloadByUrl($live->feeds_img);
            $live->feeds_img = $feedsImg['url'] ?? '';
            $live->share_path = Yii::$app->wechat->miniProgram->broadcast->getShareQrcode(['roomId' => $live->roomid]);
            // 获取推流地址
            if (empty($live->push_addr)) {
                $pushUrl = Yii::$app->wechat->miniProgram->broadcast->getPushUrl(['roomId' => $live->roomid]);
                $live->push_addr = $pushUrl['pushAddr'] ?? '';
            }
            $live->status = StatusEnum::ENABLED;
            // 已结束同步回放
            if ($live->live_status == LiveStatusEnum::END) {
                $playback = Yii::$app->wechat->miniProgram->broadcast->getPlaybacks($live->roomid, 0, 100);
                $live->playback = $playback['live_replay'] ?? '';
            }

            if (!$live->save()) {
                throw new UnprocessableEntityHttpException($this->getError($live));
            }

            $rows = [];
            $field = ['merchant_id', 'store_id', 'roomid', 'goods_id', 'status', 'created_at', 'updated_at'];
            // 插入产品
            foreach ($vo['goods'] as $value) {
                $rows[] = [
                    'merchant_id' => Yii::$app->services->merchant->getNotNullId(),
                    'store_id' => 0,
                    'roomid' => $live->roomid,
                    'goods_id' => $value['goods_id'],
                    'status' => StatusEnum::ENABLED,
                    'created_at' => time(),
                    'updated_at' => time()
                ];
            }

            // 批量插入关联
            !empty($rows) && Yii::$app->db->createCommand()->batchInsert(GoodsMap::tableName(), $field, $rows)->execute();
            $goods = array_merge($goods, $vo['goods']);
        }

        // 同步进库
        Yii::$app->wechatMiniService->liveGoods->syncByRoom($goods);

        if ($total - ($offset + $count) > 0) {
            return [
                'offset' => ($offset + $count),
                'count' => $count
            ];
        }

        return true;
    }

    /**
     * @param $room_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findRoomId($room_id)
    {
        return Live::find()
            ->where(['roomid' => $room_id])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->with(['goodsMap'])
            ->one();
    }

    /**
     * @param $room_ids
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findRoomIds($room_ids)
    {
        return Live::find()
            ->where(['in', 'roomid', $room_ids])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->with(['goods'])
            ->all();
    }

    /**
     * 进行中
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public function underway()
    {
        return Live::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['in', 'live_status' , [LiveStatusEnum::UNDERWAY]])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }
}
