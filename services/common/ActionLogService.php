<?php

namespace services\common;

use Yii;
use common\helpers\ArrayHelper;
use common\helpers\DebrisHelper;
use common\models\common\ActionLog;
use common\queues\ActionLogJob;
use common\enums\StatusEnum;
use common\components\Service;
use Zhuzhichao\IpLocationZh\Ip;

/**
 * Class ActionLogService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class ActionLogService extends Service
{
    /**
     * 队列
     *
     * @var bool
     */
    public $queueSwitch = false;

    /**
     * 行为日志
     *
     * @param $behavior
     * @param $remark
     * @param int $mapId
     * @param array $mapData
     * @param bool $noRecordData
     * @return ActionLog|string|null
     * @throws \yii\base\InvalidConfigException
     */
    public function create($behavior, $remark, $mapId = 0, $mapData = [], $noRecordData = true)
    {
        $model = new ActionLog();
        $model->behavior = $behavior;
        $model->remark = $remark;
        $model->url = DebrisHelper::getUrl();
        $model->app_id = Yii::$app->id;
        $model->get_data = Yii::$app->request->get();
        $model->post_data = $noRecordData == true ? Yii::$app->request->post() : [];
        $model->header_data = ArrayHelper::toArray(Yii::$app->request->headers);
        $model->method = Yii::$app->request->method;
        $model->module = Yii::$app->controller->module->id ?? '';
        $model->controller = Yii::$app->controller->id ?? '';
        $model->action = Yii::$app->controller->action->id ?? '';
        $model->map_id = $mapId;
        $model->map_data = $mapData;
        $model->device = Yii::$app->services->extendDetection->detectVersion();
        $model->ip = Yii::$app->services->base->getUserIp();
        $model->member_id = Yii::$app->services->member->getAutoId();
        if ($member = Yii::$app->services->member->get($model->member_id)) {
            $model->member_name = $member->nickname;
            empty($model->member_name) && $model->member_name = $member->username;
        }

        // 插件里面
        if (Yii::$app->params['inAddon'] == true) {
            $model->addon_name = Yii::$app->params['addon']['name'];
            $model->is_addon = StatusEnum::ENABLED;
        }

        if ($this->queueSwitch == true) {
            $messageId = Yii::$app->queue->push(new ActionLogJob([
                'actionLog' => $model,
            ]));

            return $messageId;
        }

        return $this->realCreate($model);
    }

    /**
     * @param ActionLog $actionLog
     */
    public function realCreate(ActionLog $actionLog)
    {
        // ip转地区
        if (!empty($actionLog->ip) && ip2long($actionLog->ip) && ($ipData = Ip::find($actionLog->ip))) {
            $actionLog->country = $ipData[0];
            $actionLog->provinces = $ipData[1];
            $actionLog->city = $ipData[2];
        }

        // !$actionLog->save() && $this->error($actionLog);
        $actionLog->save();

        return $actionLog;
    }

    /**
     * @return int|string
     */
    public function getCount($merchant_id = '')
    {
        return ActionLog::find()
                ->select('id')
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->andFilterWhere(['merchant_id' => $merchant_id])
                ->count() ?? 0;
    }

    /**
     * @param $behavior
     * @param $map_id
     * @param $addon_name
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByBehavior($behavior, $map_id, $addon_name = '')
    {
        $where = ['is_addon' => StatusEnum::DISABLED];
        if (!empty($addon_name)) {
            $where = [
                'addon_name' => $addon_name,
                'is_addon' => StatusEnum::ENABLED,
            ];
        }

        return ActionLog::find()
            ->where([
                'behavior' => $behavior,
                'map_id' => $map_id,
                'status' => StatusEnum::ENABLED
            ])
            ->andWhere($where)
            ->orderBy('id desc')
            ->asArray()
            ->all();
    }
}
