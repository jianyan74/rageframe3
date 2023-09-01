<?php

namespace services\common;

use Yii;
use yii\helpers\Json;
use common\enums\NotifyConfigTypeEnum;
use common\helpers\ArrayHelper;
use common\enums\StatusEnum;
use common\models\common\NotifyConfig;
use common\enums\AccessTokenGroupEnum;

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
     * @param $auths
     * @param $targetId
     * @param $targetType
     * @param $data
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send($notifyConfigs, $auths, $targetId, $targetType, $data)
    {
        try {
            foreach ($auths as $auth) {
                /** @var NotifyConfig $notifyConfig */
                foreach ($notifyConfigs as $notifyConfig) {
                    // ios 推送
                    if ($notifyConfig->type == NotifyConfigTypeEnum::APP_PUSH && $auth['oauth_client'] == AccessTokenGroupEnum::IOS) {
                        $this->appIosRemind(
                            $notifyConfig,
                            $auth['oauth_client_user_id'],
                            $targetId,
                            $targetType,
                            $data
                        );
                    }
                    // 安卓 推送
                    if ($notifyConfig->type == NotifyConfigTypeEnum::APP_PUSH && $auth['oauth_client'] == AccessTokenGroupEnum::ANDROID) {
                        $this->appAndroidRemind(
                            $notifyConfig,
                            $auth['oauth_client_user_id'],
                            $targetId,
                            $targetType,
                            $data
                        );
                    }
                    // 微信推送
                    if ($notifyConfig->type == NotifyConfigTypeEnum::WECHAT_MP && $auth['oauth_client'] == AccessTokenGroupEnum::WECHAT_MP) {
                        $this->wechatRemind(
                            $notifyConfig,
                            $auth['oauth_client_user_id'],
                            $data
                        );
                    }
                    // 微信小程序推送
                    if ($notifyConfig->type == NotifyConfigTypeEnum::WECHAT_MINI && $auth['oauth_client'] == AccessTokenGroupEnum::WECHAT_MINI) {
                        $this->wechatMiniProgramRemind(
                            $notifyConfig,
                            $auth['oauth_client_user_id'],
                            $data
                        );
                    }
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

        $result = Yii::$app->wechat->app->template_message->send([
            'touser' => $openid,
            'template_id' => $config->template_id,
            'url' => $url,
            'data' => $templateData,
        ]);

        // 报错调试
        if (YII_DEBUG && isset($result['errcode']) && $result['errcode'] != 0) {
            Yii::$app->services->log->push(500, 'notifyConfig', $result);
        }
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

        $result = Yii::$app->wechat->miniProgram->subscribe_message->send([
            'template_id' => $config->template_id, // 所需下发的订阅模板id
            'touser' => $openid, // 接收者（用户）的 openid
            'page' => $url, // 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
            'data' => $templateData,
        ]);

        // 报错调试
        if (YII_DEBUG && isset($result['errcode']) && $result['errcode'] != 0) {
            Yii::$app->services->log->push(500, 'notifyConfig', $result);
        }
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
     * @param $addon_name
     * @param $type
     * @param $merchant_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByAddonName($addon_name, $type = AccessTokenGroupEnum::WECHAT_MINI, $merchant_id = 0)
    {
        return NotifyConfig::find()
            ->select(['name', 'title', 'template_id'])
            ->where([
                'type' => $type,
                'status' => StatusEnum::ENABLED,
                'merchant_id' => $merchant_id,
                'addon_name' => $addon_name
            ])
            ->asArray()
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
