<?php

namespace services\common;

use common\enums\StatusEnum;
use Yii;
use yii\helpers\Json;
use common\enums\NotifyConfigTypeEnum;
use common\helpers\ArrayHelper;
use common\models\common\NotifyConfig;

/**
 * Class NotifyConfigService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyConfigService
{
    /**
     * 发送消息
     *
     * @param $notifyConfigs
     * @param $auth
     * @param $targetId
     * @param $targetType
     * @param $data
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send($notifyConfigs, $auth, $targetId, $targetType, $data)
    {
        try {
            $auth = ArrayHelper::arrayKey($auth, 'oauth_client');

            /** @var NotifyConfig $notifyConfig */
            foreach ($notifyConfigs as $notifyConfig) {
                switch ($notifyConfig->type) {
                    case NotifyConfigTypeEnum::APP_PUSH :
                        if (isset($auth['ios'])) {
                            $this->appIosRemind(
                                $notifyConfig,
                                $auth['ios']['oauth_client_user_id'],
                                $targetId,
                                $targetType,
                                $data
                            );
                        }

                        if (isset($auth['android'])) {
                            $this->appAndroidRemind(
                                $notifyConfig,
                                $auth['android']['oauth_client_user_id'],
                                $targetId,
                                $targetType,
                                $data
                            );
                        }
                        break;
                    case NotifyConfigTypeEnum::WECHAT :
                        if (isset($auth['wechat'])) {
                            $this->wechatRemind(
                                $notifyConfig,
                                $auth['wechat']['oauth_client_user_id'],
                                $data
                            );
                        }
                        break;
                    case NotifyConfigTypeEnum::WECHAT_MP :
                        if (isset($auth['wechatMp'])) {
                            $this->wechatMiniProgramRemind(
                                $notifyConfig,
                                $auth['wechatMp']['oauth_client_user_id'],
                                $data
                            );
                        }
                        break;
                    case NotifyConfigTypeEnum::DING_TALK :
                        break;
                }
            }
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            // 记录行为日志
            Yii::$app->services->log->push(500, 'notifyConfig', Yii::$app->services->base->getErrorInfo($e));
        }
    }

    /**
     * @param NotifyConfig $config
     * @param $oauth_client_user_id
     * @param $targetId
     * @param $targetType
     * @param $data
     */
    public function appIosRemind(NotifyConfig $config, $oauth_client_user_id, $targetId, $targetType, $data)
    {
        $config->title = ArrayHelper::recursionGetVal($config->title, $data);
        $config->content = ArrayHelper::recursionGetVal($config->content, $data);

        Yii::$app->services->geTui->ios($config->title, $config->content, $oauth_client_user_id, [
            'target_id' => $targetId,
            'target_type' => $targetType,
        ]);
    }

    /**
     * @param NotifyConfig $config
     * @param $oauth_client_user_id
     * @param $targetId
     * @param $targetType
     * @param $data
     */
    public function appAndroidRemind(NotifyConfig $config, $oauth_client_user_id, $targetId, $targetType, $data)
    {
        $config->title = ArrayHelper::recursionGetVal($config->title, $data);
        $config->content = ArrayHelper::recursionGetVal($config->content, $data);

        Yii::$app->services->geTui->android($config->title, $config->content, $oauth_client_user_id, [
            'target_id' => $targetId,
            'target_type' => $targetType,
        ]);
    }

    /**
     * 微信消息
     *
     * @param NotifyConfig $config
     * @param $openid
     * @param $data
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function wechatRemind(NotifyConfig $config, $openid, $data)
    {
        $templateData = [];
        $content = Json::decode($config->content);
        foreach ($content as $item) {
            $templateData[$item['key']] = [
                'value' => ArrayHelper::recursionGetVal($item['value'], $data),
                'color' => !empty($item['color']) ? $item['color'] : '#000000'
            ];
        }

        $url = ArrayHelper::recursionGetVal($config->url, $data);

        Yii::$app->wechat->app->template_message->send([
            'touser' => $openid,
            'template_id' => $config->template_id,
            'url' => $url,
            'data' => $templateData,
        ]);
    }

    /**
     * 小程序消息
     *
     * @param NotifyConfig $config
     * @param $openid
     * @param $data
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function wechatMiniProgramRemind(NotifyConfig $config, $openid, $data)
    {
        $templateData = [];
        $content = Json::decode($config->content);
        foreach ($content as $item) {
            $templateData[$item['key']] = [
                'value' => ArrayHelper::recursionGetVal($item['value'], $data),
                'color' => !empty($item['color']) ? $item['color'] : '#000000'
            ];
        }

        $url = ArrayHelper::recursionGetVal($config->url, $data);

        Yii::$app->wechat->miniProgram->subscribe_message->send([
            'template_id' => $config->template_id, // 所需下发的订阅模板id
            'touser' => $openid, // 接收者（用户）的 openid
            'page' => $url, // 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
            'data' => $templateData,
        ]);
    }

    /**
     * @param $name
     * @param $merchant_id
     * @param $addon_name
     * @return array|\yii\db\ActiveRecord[]|NotifyConfig
     */
    public function findByName($name, $merchant_id, $addon_name = '')
    {
        return NotifyConfig::find()
            ->where([
                'name' => $name,
                'status' => StatusEnum::ENABLED,
                'merchant_id' => $merchant_id
            ])
            ->andFilterWhere(['addon_name' => $addon_name])
            ->all();
    }

    /**
     * @param $name
     * @param $merchant_id
     * @param $addon_name
     * @return array|\yii\db\ActiveRecord[]|NotifyConfig
     */
    public function findSysByName($name, $merchant_id, $addon_name = '')
    {
        $data = NotifyConfig::find()
            ->where([
                'name' => $name,
                'type' => NotifyConfigTypeEnum::SYS,
                'status' => StatusEnum::ENABLED,
                'merchant_id' => $merchant_id
            ])
            ->andFilterWhere(['addon_name' => $addon_name])
            ->one();

        if (!$data) {
            $data = new NotifyConfig();
            $data = $data->loadDefaultValues();
        }

        return $data;
    }
}
