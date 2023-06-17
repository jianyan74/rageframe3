<?php

namespace services\extend\logistics;

use Yii;
use yii\web\NotFoundHttpException;
use common\components\Service;
use common\forms\LogisticsForm;
use common\enums\LogisticsTypeEnum;
use common\helpers\StringHelper;

/**
 * 物流查询
 *
 * $order = Yii::$app->-service->extendLogistics->aliyun($no);
 *
 * $order->code; // 状态码
 * $order->message; // 状态信息
 * $order->company; // 物流公司简称
 * $order->no; // 物流单号
 * $order->status; // 当前物流单状态
 *
 * 注：物流状态可能不一定准确
 *
 * $order->getDisplayStatus(); // TODO 当前物流单状态展示名
 * $order->getAbstractStatus(); // TODO 当前抽象物流单状态
 * $order->getCourier(); // TODO 快递员姓名
 * $order->getCourierPhone(); // TODO 快递员手机号
 * $order->list; // 物流单状态详情
 * $order->original; // 获取接口原始返回信息
 *
 * Class LogisticsService
 * @package services\extend
 */
class LogisticsService extends Service
{
    /**
     * @param $expressNo
     * @param $expressCompany
     * @param int $customerName 手机号码
     * @param $isCache
     * @return array
     * @throws NotFoundHttpException
     */
    public function query($expressNo, $expressCompany = null, $customerName = null, $isCache = false)
    {
        if (empty($expressNo)) {
            return [];
        }

        try {
            $logisticsDefault = Yii::$app->services->config->backendConfig('logistics_default');
            if (!in_array($logisticsDefault, LogisticsTypeEnum::getKeys())) {
                throw new NotFoundHttpException('无效的物流服务');
            }

            $result = $this->$logisticsDefault($expressNo, $expressCompany, $customerName, $isCache);
            return $result->list;
        } catch (\Exception $e) {
            if (YII_DEBUG) {
                throw new NotFoundHttpException($e->getMessage());
            }
        }

        return [];
    }

    /**
     * 阿里云
     *
     * @param string $no 快递单号
     * @param null $company
     * @return LogisticsForm
     */
    public function aliyun($no, $company = null, $customerName = null, $isCache = false)
    {
        // 顺丰快递
        if (!empty($customerName) && StringHelper::strExists($no, 'SF')) {
            $no = $no . ':' . substr($customerName, -4);
        }

        // 避免填错，选择自动处理
        $company = null;

        return $this->providerQuery($no, $company, $customerName, 'extendLogisticsALiYun', $isCache);
    }

    /**
     * 聚合
     *
     * @param string $no 快递单号
     * @param string $company 可选（建议必填，不填查询结果不一定准确）
     * @return LogisticsForm
     */
    public function juhe($no, $company, $customerName = null, $isCache = false)
    {
        // 避免填错，选择自动处理
        $company = null;

        return $this->providerQuery($no, $company, $customerName, 'extendLogisticsJuHe', $isCache);
    }

    /**
     * 快递鸟
     *
     * @param string $no 快递单号
     * @param string $company 可选（建议必填，不填查询结果不一定准确）
     * @return LogisticsForm
     */
    public function kdniao($no, $company = null, $customerName = null, $isCache = false)
    {
        // 顺丰快递
        if (!empty($customerName) && StringHelper::strExists($no, 'SF')) {
            $customerName = substr($customerName, -4);
        }

        // 避免填错，选择自动处理
        $company = null;

        return $this->providerQuery($no, $company, $customerName, 'extendLogisticsKdn', $isCache);
    }

    /**
     * 快递100
     *
     * @param string $no 快递单号
     * @param string $company 可选（建议必填，不填查询结果不一定准确）
     * @return LogisticsForm
     */
    public function kd100($no, $company = null, $customerName = null, $isCache = false)
    {
        // 避免填错，选择自动处理
        $company = null;

        return $this->providerQuery($no, $company, $customerName, 'extendLogisticsKd100', $isCache);
    }

    /**
     * 获取对应的可用快递公司名称
     *
     * @param $provider
     * @throws NotFoundHttpException
     */
    public function companies($provider)
    {
        return Yii::$app->services->$provider->companies();
    }

    /**
     * 查询
     *
     * @param $no
     * @param $company
     * @param $provider
     * @return mixed
     */
    protected function providerQuery($no, $company, $customerName, $provider, $isCache)
    {
        if ($isCache == false) {
            return Yii::$app->services->$provider->query($no, $company, $customerName);
        }

        $key = 'Logistics|' .  $no;
        if (!($data = Yii::$app->cache->get($key))) {
            $data = Yii::$app->services->$provider->query($no, $company, $customerName);
            Yii::$app->cache->set($key, $data, 60 * 60);
        }

        return $data;
    }
}
