<?php

namespace backend\controllers;

use Yii;
use common\helpers\FileHelper;
use common\helpers\ResultHelper;
use backend\forms\ClearCache;

/**
 * 主控制器
 *
 * Class MainController
 * @package backend\controllers
 */
class MainController extends BaseController
{
    /**
     * 系统首页
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderPartial($this->action->id, []);
    }

    /**
     * 子框架默认主页
     *
     * @return string
     */
    public function actionHome()
    {
        return $this->render($this->action->id, [
            'memberCount' => Yii::$app->services->member->getCountByType(),
            'memberAccount' => Yii::$app->services->memberAccount->getSumByType(),
            'actionLogCount' => Yii::$app->services->actionLog->getCount(),
        ]);
    }

    /**
     * 用户指定时间内数量
     *
     * @param $type
     * @return array
     */
    public function actionMemberBetweenCount($type)
    {
        $data = Yii::$app->services->member->getBetweenCountStat($type);

        return ResultHelper::json(200, '获取成功', $data);
    }

    /**
     * 充值统计
     *
     * @param $type
     * @return array
     */
    public function actionMemberRechargeStat($type)
    {
        $data = Yii::$app->services->memberCreditsLog->getRechargeStat($type);

        return ResultHelper::json(200, '获取成功', $data);
    }

    /**
     * 用户指定时间内消费日志
     *
     * @param $type
     * @return array
     */
    public function actionMemberCreditsLogBetweenCount($type)
    {
        $data = Yii::$app->services->memberCreditsLog->getBetweenCountStat($type);

        return ResultHelper::json(200, '获取成功', $data);
    }

    /**
     * 系统信息
     *
     * @return string
     */
    public function actionSystem()
    {
        // 禁用函数
        $disableFunctions = ini_get('disable_functions');
        $disableFunctions = !empty($disableFunctions) ? explode(',', $disableFunctions) : '未禁用';
        // 附件大小
        $attachmentSize = FileHelper::getDirSize(Yii::getAlias('@attachment'));

        return $this->render($this->action->id, [
            'mysqlSize' => Yii::$app->services->base->getDefaultDbSize(),
            'attachmentSize' => $attachmentSize ?? 0,
            'disableFunctions' => $disableFunctions,
        ]);
    }

    /**
     * 清理缓存
     *
     * @return string
     */
    public function actionClearCache()
    {
        $model = new ClearCache();
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->message('清理成功', $this->refresh())
                : $this->message($this->getError($model), $this->refresh(), 'error');
        }

        return $this->render($this->action->id, [
            'model' => $model
        ]);
    }
}
