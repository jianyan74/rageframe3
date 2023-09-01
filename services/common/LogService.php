<?php

namespace services\common;

use Yii;
use common\models\common\Log;
use common\enums\AppEnum;
use common\enums\MessageLevelEnum;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\helpers\DebrisHelper;
use common\queues\LogJob;
use common\helpers\EchantsHelper;
use common\enums\SubscriptionActionEnum;

/**
 * Class LogService
 * @package services\common
 */
class LogService
{
    /**
     * 丢进队列
     *
     * @var bool
     */
    public $queueSwitch = false;

    /**
     * 不记录的状态码
     *
     * @var array
     */
    public $exceptCode = [];

    /**
     * @param $error_code
     * @param $error_msg
     * @param $error_data
     * @return Log|false|string|null
     * @throws \yii\base\InvalidConfigException
     */
    public function push($error_code, $error_msg, $error_data)
    {
        $log = $this->initData();
        $log->error_code = $error_code;
        $log->error_msg = $error_msg;
        $log->error_data = $error_data;

        try {
            // 判断是否开启队列
            if ($this->queueSwitch == true) {
                $message_id = Yii::$app->queue->push(new LogJob([
                    'log' => $log,
                ]));

                return $message_id;
            }

            return $this->realCreate($log);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 日志记录
     *
     *
     * @param $response
     * @param bool $showReqId
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    public function record($response, $showReqId = false)
    {
        $errData = [];
        // 判断是否记录日志
        if (in_array($this->getLevel($response->statusCode), Yii::$app->params['user.log.level'])) {
             if (is_array($response->statusText)) {
                 $errData = $response->statusText;
                 $errorMessage = $errData['errorMessage'] ?? '';
                 $showReqId && $response->data['req_id'] = Yii::$app->params['uuid'];
             } else {
                 $errorMessage = $response->statusText;
             }

            // 检查是否报错
            if (
                empty($errData) &&
                $response->statusCode >= 300 &&
                ($exception = Yii::$app->getErrorHandler()->exception)
            ) {
                $errData = [
                    'type' => get_class($exception),
                    'file' => method_exists($exception, 'getFile') ? $exception->getFile() : '',
                    'errorMessage' => $exception->getMessage(),
                    'line' => $exception->getLine(),
                    'stack-trace' => explode("\n", $exception->getTraceAsString()),
                ];

                $errorMessage = $exception->getMessage();
                $showReqId && $response->data['req_id'] = Yii::$app->params['uuid'];
            }

            // 排除状态码
            if (
                Yii::$app->params['user.log'] &&
                !Yii::$app->request->isOptions &&
                !in_array($response->statusCode, ArrayHelper::merge($this->exceptCode, Yii::$app->params['user.log.except.code']))
            ) {
                $this->push($response->statusCode, $errorMessage, $errData);
            }
        }

        return $errData;
    }

    /**
     * 初始化数据
     *
     * @return Log
     * @throws \yii\base\InvalidConfigException
     */
    public function initData()
    {
        $model = new Log();
        $model->url = DebrisHelper::getUrl();
        $model->app_id = Yii::$app->id;
        $model->get_data = Yii::$app->request->get();
        $model->post_data = Yii::$app->request->post();
        $model->header_data = ArrayHelper::toArray(Yii::$app->request->headers);
        $model->method = Yii::$app->request->method;
        $model->module = Yii::$app->controller->module->id ?? '';
        $model->controller = Yii::$app->controller->id ?? '';
        $model->action = Yii::$app->controller->action->id ?? '';
        $model->req_id = Yii::$app->params['uuid'];
        $model->device = Yii::$app->services->extendDetection->detectVersion();
        $model->ip = Yii::$app->services->base->getUserIp();
        if (in_array(Yii::$app->id, AppEnum::api())) {
            $model->member_id = Yii::$app->user->identity->member_id ?? 0;
        } else {
            $model->member_id = Yii::$app->user->id ?? 0;
            $model->member_name = Yii::$app->user->nickname ?? '';
        }

        // 插件里面
        if (Yii::$app->params['inAddon'] == true) {
            $model->addon_name = Yii::$app->params['addon']['name'];
            $model->is_addon = StatusEnum::ENABLED;
        }

        return $model;
    }

    /**
     * 真实写入
     *
     * @param $data
     */
    public function realCreate(Log $log)
    {
        $log->save();

        // 记录风控日志
        // Yii::$app->services->reportLog->create($log);

        // 提醒
        $log->error_code >= 500 && Yii::$app->services->notify->sendRemind($log->id, SubscriptionActionEnum::ERROR, 0, [], '/common/log/view?id=' . $log->id);

        return $log;
    }

    /**
     * 状态报表统计
     *
     * @param $type
     * @return array
     */
    public function stat($type)
    {
        $fields = [];
        $codes = [400, 401, 403, 404, 405, 422, 429, 500];
        foreach ($codes as $code) {
            $fields[$code] = $code . '错误';
        }

        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime($type);

        // 获取数据
        return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) use ($codes) {
            $statData = Log::find()
                ->select(["from_unixtime(created_at, '$formatting') as time", 'count(id) as count', 'error_code'])
                ->andWhere(['between', 'created_at', $start_time, $end_time])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->andWhere(['in', 'error_code', $codes])
                ->andFilterWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
                ->groupBy(['time', 'error_code'])
                ->asArray()
                ->all();

            return EchantsHelper::regroupTimeData($statData, 'error_code');
        }, $fields, $time, $format);
    }

    /**
     * 流量报表统计
     *
     * @param $type
     * @return array
     */
    public function flowStat($type)
    {
        $fields = [
            'count' => '访问量(PV)',
            'member_id' => '访问人数(UV)',
            'ip' => '访问 IP',
        ];

        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime($type);

        // 获取数据
        return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) {
            return Log::find()
                ->select(["from_unixtime(created_at, '$formatting') as time", 'count(id) as count', 'count(distinct(ip)) as ip', 'count(distinct(member_id)) as member_id'])
                ->andWhere(['between', 'created_at', $start_time, $end_time])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->andFilterWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
                ->groupBy(['time'])
                ->asArray()
                ->all();
        }, $fields, $time, $format);
    }

    /**
     * 获取报错级别
     *
     * @param $statusCode
     * @return bool|string
     */
    private function getLevel($statusCode)
    {
        if ($statusCode < 300) {
            return MessageLevelEnum::SUCCESS;
        } elseif ($statusCode >= 300 && $statusCode < 400) {
            return MessageLevelEnum::INFO;
        } elseif ($statusCode >= 400 && $statusCode < 500) {
            return MessageLevelEnum::WARNING;
        } elseif ($statusCode >= 500) {
            return MessageLevelEnum::ERROR;
        }

        return MessageLevelEnum::ERROR;
    }
}
