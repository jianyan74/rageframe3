<?php

namespace services\extend\push;

use Yii;
use common\enums\AppPushEnum;
use common\enums\AccessTokenGroupEnum;
use common\models\member\Auth;
use common\queues\AppPushJob;

/**
 * app 推送
 *
 * Class AppPushService
 * @package services\extend
 */
class AppPushService
{
    /**
     * @var bool
     */
    public $queueSwitch = false;

    /**
     * 推送类型
     *
     * @var string
     */
    protected $client;

    /**
     * @param $type
     */
    public function client($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @param $title
     * @param $content
     * @param Auth $auth
     * @param array $transmissionContent
     * @return string|void|null
     * @throws \yii\base\InvalidConfigException
     */
    public function push($title, $content, Auth $auth, $transmissionContent = [])
    {
        if (empty($type = $this->client)) {
            $type = Yii::$app->services->config->backendConfig('plus_plus');
        }

        // 是否进入队列
        if ($this->queueSwitch == true) {
            $messageId = Yii::$app->queue->push(new AppPushJob([
                'title' => $title,
                'content' => $content,
                'type' => $type,
                'auth' => $auth,
                'transmissionContent' => $transmissionContent,
            ]));

            return $messageId;
        } else {
            $this->realPlus($type, $title, $content, $auth, $transmissionContent);
        }
    }

    /**
     *
     * @param $type
     * @param $title
     * @param $content
     * @param Auth $auth
     * @param array $transmissionContent
     * @throws \yii\base\InvalidConfigException
     */
    public function realPlus($type, $title, $content, Auth $auth, $transmissionContent = [])
    {
        switch ($type) {
            case AppPushEnum::GE_TUI :
                // 个推
                if ($auth->oauth_client == AccessTokenGroupEnum::IOS) {
                    Yii::$app->services->extendGeTui->client()->ios($title, $content, $auth->oauth_client_user_id, $transmissionContent);
                } else {
                    Yii::$app->services->extendGeTui->client()->android($title, $content, $auth->oauth_client_user_id, $transmissionContent);
                }
                break;
            case AppPushEnum::J_PUSH :
                // 极光推送
                Yii::$app->services->extendJPush->client()->send($title, $content, $auth->oauth_client_user_id, $transmissionContent);
                break;
        }
    }
}
