<?php

namespace services\extend;

use Yii;
use AppleSignIn\ASDecoder;
use linslin\yii2\curl\Curl;
use yii\helpers\Json;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class OpenPlatformService
 * @package services\extend
 */
class OpenPlatformService
{
    /**
     * 微信开放平台
     *
     * @param string $code
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     *
     * Array
     * (
     *      [openid] => ''
     *      [nickname] => ''
     *      [sex] => 1
     *      [language] => zh_CN
     *      [city] => 杭州
     *      [province] => 浙江
     *      [country] => 中国
     *      [headimgurl] => ''
     *      [privilege] => Array
     *      (
     *      )
     *      [unionid] => ''
     * )
     */
    public function wechat(string $code)
    {
        $appId = Yii::$app->services->config->backendConfig('wechat_app_id'); //开发平台申请
        $appSecret = Yii::$app->services->config->backendConfig('wechat_app_secret'); //开发平台申请

        // 认证
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appId . "&secret=" . $appSecret . "&code=" . $code . "&grant_type=authorization_code";
        // 调用微信api
        $http = new Curl();
        $result = Json::decode($http->get($url));
        Yii::$app->services->base->getWechatError($result);
        // 拉取用户信息
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $result['access_token'] . "&openid=" . $result['openid'] . "&lang=zh_CN";

        return Json::decode($http->get($url));
    }

    /**
     * apple 登录
     *
     * @param string $clientUser openid
     * @param string $identityToken jwt
     * @return array
     * @throws UnprocessableEntityHttpException
     */
    public function apple(string $clientUser, string $identityToken)
    {
        $appleSignInPayload = ASDecoder::getAppleSignInPayload($identityToken);

        /**
         * Obtain the Sign In with Apple email and user creds.
         */
        $email = $appleSignInPayload->getEmail();
        $user = $appleSignInPayload->getUser();

        /**
         * Determine whether the client-provided user is valid.
         */
        $isValid = $appleSignInPayload->verifyUser($clientUser);
        if ($isValid == false) {
            throw new UnprocessableEntityHttpException('验证失败');
        }

        return [$email, $user];
    }
}
