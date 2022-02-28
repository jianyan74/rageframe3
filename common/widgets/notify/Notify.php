<?php

namespace common\widgets\notify;

use Yii;
use yii\base\Widget;

/**
 * Class Notify
 * @package common\widgets\notify
 * @author jianyan74 <751393839@qq.com>
 */
class Notify extends Widget
{
    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function run()
    {
        // 拉取公告
        Yii::$app->services->notify->pullAnnounce(Yii::$app->user->identity->merchant_id, Yii::$app->user->identity->created_at);
        // 获取当前通知
        list($notify, $count) = Yii::$app->services->notifyMember->getNotReadNotify(Yii::$app->user->identity->merchant_id);

        return $this->render('notify', [
            'notify' => $notify,
            'count' => $count,
        ]);
    }
}