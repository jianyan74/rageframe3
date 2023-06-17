<?php

namespace services\extend;

use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use common\models\extend\sms\TencentSms;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\helpers\EchantsHelper;
use common\models\extend\SmsLog;
use common\queues\SmsJob;
use Overtrue\EasySms\EasySms;

/**
 * 短信
 *
 * Class SmsService
 * @package services\extend
 * @author jianyan74 <751393839@qq.com>
 */
class SmsService
{
    /**
     * @var bool
     */
    public $queueSwitch = false;

    /**
     * @var
     */
    protected $config;

    /**
     * 发送短信
     *
     * ```php
     *       Yii::$app->services->extendSms->send($mobile, $code, $usage, $member_id)
     * ```
     *
     * @param int $mobile 手机号码
     * @param int $code 验证码
     * @param string $usage 用途
     * @param int $member_id 用户ID
     * @return SmsLog|string|null
     * @throws UnprocessableEntityHttpException
     */
    public function send($mobile, $code, $usage, $member_id = 0)
    {
        $this->initSms();

        $ip = Yii::$app->services->base->getUserIp();
        if ($this->queueSwitch == true) {
            $messageId = Yii::$app->queue->push(new SmsJob([
                'mobile' => $mobile,
                'code' => $code,
                'usage' => $usage,
                'member_id' => $member_id,
                'ip' => $ip
            ]));

            return $messageId;
        }

        return $this->realSend($mobile, $code, $usage, $member_id, $ip);
    }

    /**
     * 真实发送短信
     *
     * @param $mobile
     * @param $code
     * @param $usage
     * @param int $member_id
     * @param string $ip
     * @return SmsLog
     * @throws UnprocessableEntityHttpException
     */
    public function realSend($mobile, $code, $usage, $member_id = 0, $ip = '')
    {
        !empty($this->template) && $this->template = ArrayHelper::map(Json::decode($this->template), 'group', 'template');
        is_array($this->template) && $templateID = $this->template[$usage] ?? '';

        try {
            // 校验发送是否频繁
            if (($smsLog = $this->findByMobile($mobile)) && $smsLog['created_at'] + 60 > time()) {
                throw new NotFoundHttpException('请不要频繁发送短信');
            }

            $easySms = new EasySms($this->config);
            // Tencent SMS
            $easySms->extend('tencent', function($gatewayConfig){
                return new TencentSms($gatewayConfig);
            });

            $result = $easySms->send($mobile, [
                'template' => $templateID,
                'data' => [
                    'code' => $code,
                ],
            ]);

            $this->saveLog([
                'mobile' => $mobile,
                'code' => $code,
                'member_id' => $member_id,
                'usage' => $usage,
                'ip' => $ip,
                'error_code' => 200,
                'error_msg' => 'ok',
                'error_data' => Json::encode($result),
            ]);
        } catch (NotFoundHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            $errorMessage = [];
            $exceptions = $e->getExceptions();
            $gateways = $this->config['default']['gateways'];

            foreach ($gateways as $gateway) {
                if (isset($exceptions[$gateway])) {
                    $errorMessage[$gateway] = $exceptions[$gateway]->getMessage();
                }
            }

            $this->saveLog([
                'mobile' => $mobile,
                'code' => $code,
                'member_id' => $member_id,
                'usage' => $usage,
                'ip' => $ip,
                'error_code' => 422,
                'error_msg' => '发送失败',
                'error_data' => Json::encode($errorMessage),
            ]);

            throw new UnprocessableEntityHttpException('短信发送失败');
        }
    }

    /**
     * @param $type
     * @return array
     */
    public function stat($type)
    {
        $fields = [
            'count' => '异常发送数量'
        ];

        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime($type);
        // 获取数据
        return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) {
            return SmsLog::find()
                ->select(["from_unixtime(created_at, '$formatting') as time", 'count(id) as count'])
                ->andWhere(['between', 'created_at', $start_time, $end_time])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->andWhere(['>', 'error_code', 399])
                ->andFilterWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
                ->groupBy(['time'])
                ->asArray()
                ->all();
        }, $fields, $time, $format);
    }

    /**
     * @param $mobile
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByMobile($mobile)
    {
        return SmsLog::find()
            ->where(['mobile' => $mobile])
            ->orderBy('id desc')
            ->asArray()
            ->one();
    }

    /**
     * @param array $data
     * @return SmsLog
     */
    protected function saveLog($data = [])
    {
        $log = new SmsLog();
        $log = $log->loadDefaultValues();
        $log->attributes = $data;
        $log->save();

        return $log;
    }

    /**
     * 初始化
     */
    protected function initSms()
    {
        $config = Yii::$app->services->config->backendConfigAll();
        $gateway = $config['sms_default'] ?? 'aliyun';

        // 模板
        if ($gateway == 'aliyun') {
            $this->template = $config['sms_aliyun_template'] ?? '';
        } else {
            $this->template = $config['sms_tencent_template'] ?? '';
        }

        $this->config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,
            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,
                // 默认可用的发送网关
                'gateways' => [$gateway],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => Yii::getAlias('runtime') . '/easy-sms.log',
                ],
                'aliyun' => [
                    'access_key_id' => $config['sms_aliyun_accesskeyid'] ?? '',
                    'access_key_secret' => $config['sms_aliyun_accesskeysecret'] ?? '',
                    'sign_name' => $config['sms_aliyun_sign_name'] ?? '',
                ],
                'tencent' => [
                    'access_app_id' => $config['sms_tencent_accessappid'] ?? '',
                    'access_key_id' => $config['sms_tencent_accesskeyid'] ?? '',
                    'access_key_secret' => $config['sms_tencent_accesskeysecret'] ?? '',
                    'sign_name' => $config['sms_tencent_sign_name'] ?? '',
                ],
            ],
        ];
    }
}
