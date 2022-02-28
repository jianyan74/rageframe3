<?php

namespace common\models\extend\sms;

use yii\helpers\Json;
use yii\web\UnprocessableEntityHttpException;
use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;
use Overtrue\EasySms\Gateways\Gateway;
use Overtrue\EasySms\Support\Config;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Sms\V20190711\SmsClient;
use TencentCloud\Sms\V20190711\Models\SendSmsRequest;

/**
 * Class TencentSms
 * @package common\models\extend\sms
 */
class TencentSms extends Gateway
{
    public $config = [];

    /**
     * @param PhoneNumberInterface $to
     * @param MessageInterface $message
     * @param Config $config
     * @return bool
     * @throws UnprocessableEntityHttpException
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config)
    {
        $this->config = [
            'appid' => $config->get('access_app_id'),
            'secret_id' => $config->get('access_key_id'),
            'secret_key' => $config->get('access_key_secret'),
        ];

        try {
            $cred = new Credential($this->config['secret_id'], $this->config['secret_key']);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("sms.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new SmsClient($cred, "ap-guangzhou", $clientProfile);
            $req = new SendSmsRequest();

            $params = [
                "PhoneNumberSet" => [
                    '86'.$to->getNumber(),
                ],
                "TemplateID" => $message->getTemplate(),
                "Sign" => $config->get('sign_name'),
                "TemplateParamSet" => [
                    (string)$message->getData()['code'],
                ],
                "SmsSdkAppid" => $this->config['appid'],
            ];

            $req->fromJsonString(Json::encode($params));
            $resp = $client->SendSms($req);
            $result = $resp->toJsonString();
            $result = Json::decode($result);
        } catch (TencentCloudSDKException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        if (isset($result['SendStatusSet'][0]['Code']) && $result['SendStatusSet'][0]['Code'] == 'Ok') {
            return true;
        }

        throw new UnprocessableEntityHttpException($result['SendStatusSet'][0]['Message'] ?? '发送失败');
    }
}
