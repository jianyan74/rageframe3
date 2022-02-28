<?php

namespace services\common;

use Yii;
use common\enums\MemberTypeEnum;
use common\components\Service;
use common\enums\NotifyTypeEnum;
use common\enums\StatusEnum;
use common\models\common\Notify;
use common\models\common\NotifyMember;
use common\helpers\ArrayHelper;
use common\enums\SubscriptionActionEnum;
use common\enums\NotifyConfigTypeEnum;

/**
 * Class NotifyService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyService extends Service
{
    /**
     * 解析数据
     *
     * @param $data
     * @return array|array[]|object|object[]|string|string[]
     * @throws \yii\base\InvalidConfigException
     */
    public function analysisData($data)
    {
        $data = ArrayHelper::toArray($data);
        $data['time'] = Yii::$app->formatter->asDatetime(time());
        $data['ip'] = Yii::$app->services->base->getUserIp();

        return $data;
    }

    /**
     * 发送提醒
     *
     * @param $target_id
     * @param $targetType
     * @param int $merchantId
     * @param array $data
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
     */
    public function sendRemind($target_id, $targetType, $merchantId = 0, $data = [], $link = '')
    {
        $data = $this->analysisData($data);
        $addonName = Yii::$app->params['addon']['name'] ?? '';
        $config = Yii::$app->services->notifyConfig->findByName($targetType, $merchantId, $addonName);
        $sys = [];
        foreach ($config as $value) {
            if (empty($value->content)) {
                $value->attributes = SubscriptionActionEnum::default($targetType);
            }

            if (NotifyConfigTypeEnum::SYS == $value->type) {
                $sys = $value;
                $sys->content = ArrayHelper::recursionGetVal($value->content, $data);
            }
        }

        // 用户授权
        $auth = Yii::$app->services->memberAuth->findByMerchantId($merchantId, $merchantId > 0 ? MemberTypeEnum::MERCHANT : MemberTypeEnum::MANAGER);
        if (!empty($auth)) {
            Yii::$app->services->notifyConfig->send($config, $auth, $target_id, $targetType, $data);
            Yii::$app->services->merchant->setId($merchantId);
        }

        !empty($sys) && $this->createRemind($sys->content, $target_id, $targetType, $merchantId, $link);
    }

    /**
     * 创建一条提醒
     *
     * @param $content
     * @param $target_id
     * @param $target_type
     * @return false
     */
    public function createRemind($content, $target_id, $target_type, $merchant_id, $link = '')
    {
        $model = new Notify();
        $model->target_id = $target_id;
        $model->target_type = $target_type;
        $model->merchant_id = $merchant_id;
        $model->action = $target_type;
        $model->sender_id = 0;
        $model->link = $link;
        $model->type = NotifyTypeEnum::REMIND;
        $model->title = SubscriptionActionEnum::getValue($target_type);
        $model->content = $content;
        if ($model->save()) {
            $notifyMember = new NotifyMember();
            $notifyMember->notify_id = $model->id;
            $notifyMember->merchant_id = $merchant_id;
            $notifyMember->member_id = 0;
            $notifyMember->type = NotifyTypeEnum::REMIND;
            $notifyMember->save();
        }

        return false;
    }

    /**
     * 创建一条信息(私信)
     *
     * @param int $sender_id 触发id
     * @param string $content 内容
     * @param int $receiver 接收id
     */
    public function createMessage($content, $sender_id, $receiver)
    {
        $model = new Notify();
        $model->content = $content;
        $model->sender_id = $sender_id;
        $model->type = NotifyTypeEnum::MESSAGE;
        if ($model->save()) {
            $NotifyMember = new NotifyMember();
            $NotifyMember->notify_id = $model->id;
            $NotifyMember->member_id = $receiver;
            $NotifyMember->type = NotifyTypeEnum::MESSAGE;

            return $NotifyMember->save();
        }

        return false;
    }

    /**
     * 创建公告
     *
     * @param $title
     * @param $status
     * @param $target_id
     * @return bool
     */
    public function createAnnounce($title, $status, $target_id)
    {
        $model = Notify::find()
            ->where([
                'target_id' => $target_id,
                'type' => NotifyTypeEnum::ANNOUNCE,
            ])
            ->one();

        if (empty($model)) {
            $model = new Notify();
            $model = $model->loadDefaultValues();
            $model->type = NotifyTypeEnum::ANNOUNCE;
            $model->target_id = $target_id;
        }

        $model->title = $title;
        $model->status = $status;

        return $model->save();
    }

    /**
     * 拉取公告
     *
     * @param int $merchant_id 商户ID
     * @throws \yii\db\Exception
     */
    public function pullAnnounce($merchant_id, $created_at)
    {
        // 从 UserNotify 中获取最近的一条公告信息的创建时间: lastTime
        $model = NotifyMember::find()
            ->where(['merchant_id' => $merchant_id, 'type' => NotifyTypeEnum::ANNOUNCE])
            ->orderBy('id desc')
            ->asArray()
            ->one();

        // 用 lastTime 作为过滤条件，查询 Notify 的公告信息
        $lastTime = $model ? $model['created_at'] : $created_at;
        $notifies = Notify::find()
            ->where(['type' => NotifyTypeEnum::ANNOUNCE, 'status' => StatusEnum::ENABLED])
            ->andWhere(['>', 'created_at', $lastTime])
            ->asArray()
            ->all();

        // 新建 UserNotify 并关联查询出来的公告信息
        $rows = [];
        $fields = ['notify_id', 'merchant_id', 'app_id', 'type', 'created_at', 'updated_at'];
        $appId = Yii::$app->id;
        foreach ($notifies as $notify) {
            $rows[] = [$notify['id'], $merchant_id, $appId, NotifyTypeEnum::ANNOUNCE, $notify['created_at'], time()];
        }

        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(NotifyMember::tableName(), $fields, $rows)->execute();
    }
}
