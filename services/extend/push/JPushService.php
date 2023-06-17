<?php

namespace services\extend\push;

use Yii;
use common\components\Service;
use common\helpers\FileHelper;
use JPush\Client as JPush;

/**
 * Class JPushService
 * @package services\extend\push
 * @author jianyan74 <751393839@qq.com>
 */
class JPushService extends Service
{
    /**
     * @var \JPush\Client
     */
    protected $client;

    /**
     * @return $this
     */
    public function client($config = [])
    {
        $logPath = Yii::getAlias('@runtime') . '/logs/j-push/' . date('Y-m') . '/';
        FileHelper::mkdirs($logPath);

        $this->client = new JPush(
            Yii::$app->services->config->backendConfig('push_jpush_appid'),
            Yii::$app->services->config->backendConfig('push_jpush_app_secret'),
            $logPath . date('d') . '.log'
        );

        return $this;
    }

    /**
     * @param $title
     * @param $content
     * @param $clientId
     * @param array $transmissionContent
     * @throws \yii\base\InvalidConfigException
     */
    public function send($title, $content, $clientId, $transmissionContent = [])
    {
        $pusher = $this->client->push();
        $pusher->setPlatform($clientId ?? 'all');
        $pusher->addAllAudience();
        $pusher->setNotificationAlert($title);
        try {
            $pusher->send();
        } catch (\JPush\Exceptions\JPushException $e) {
            Yii::$app->services->log->push(500, 'JPush', Yii::$app->services->base->getErrorInfo($e));
        }
    }

    /**
     * 推送指定ID
     *
     * @param string $content 推送内容
     * @param array $ids 推送的id
     * @param string $info 业务内容
     * @param string $title 推送标题 定向的简单推送 不填
     * @throws \yii\base\InvalidConfigException
     */
    public function sendToCourier($content, $ids = [], $info = '', $title = '')
    {
        try {
            $this->client->push()
                ->setPlatform(['ios', 'android'])
                ->addRegistrationId($ids)
                ->iosNotification([
                    "title" => $title,
                    "body" => $content
                ], [
                    'sound' => 'sound.caf',
                    'badge' => '+1',
                    'content-available' => true,
                    'mutable-content' => true,
                    'category' => 'jiguang',
                    'extras' => [
                        'info' => $info,
                    ],
                ])
                ->androidNotification($content, [
                    'title' => $title,
                    'extras' => [
                        'info' => $info,
                    ],
                ])
                ->options([
                    // True 表示推送生产环境，False 表示要推送开发环境；如果不指定则默认为推送开发环境
                    'apns_production' => false,
                ])
                ->send();
        } catch (\Exception $e) {
            Yii::$app->services->log->push(500, 'JPush', Yii::$app->services->base->getErrorInfo($e));
        }
    }
}
