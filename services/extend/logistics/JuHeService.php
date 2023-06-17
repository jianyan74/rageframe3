<?php

namespace services\extend\logistics;

use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use common\forms\LogisticsForm;
use linslin\yii2\curl\Curl;

/**
 * 聚合物流查询
 *
 * Class JuHeService
 * @package services\extend\logistics
 * @author jianyan74 <751393839@qq.com>
 * @deprecated 接口已停用
 */
class JuHeService
{
    const URL = 'https://v.juhe.cn/exp';

    const STATUS_PENDING = 'PENDING';
    const STATUS_NO_RECORD = 'NO_RECORD';
    const STATUS_IN_TRANSIT = 'IN_TRANSIT';
    const STATUS_DELIVERING = 'DELIVERING';
    const STATUS_SIGNED = 'SIGNED';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_PROBLEM = 'PROBLEM';
    const STATUS_INVALID = 'INVALID';
    const STATUS_TIMEOUT = 'TIMEOUT';
    const STATUS_FAILED = 'FAILED';
    const STATUS_SEND_BACK = 'SEND_BACK';
    const STATUS_TAKING = 'TAKING';

    /**
     * @var string[]
     */
    public $statusMap = [
        self::STATUS_PENDING => '待查询',
        self::STATUS_NO_RECORD => '无记录',
        self::STATUS_IN_TRANSIT => '运输中',
        self::STATUS_DELIVERING => '派送中',
        self::STATUS_SIGNED => '已签收',
        self::STATUS_REJECTED => '拒签',
        self::STATUS_PROBLEM => '疑难件',
        self::STATUS_INVALID => '无效件',
        self::STATUS_TIMEOUT => '超时件',
        self::STATUS_FAILED => '派送失败',
        self::STATUS_SEND_BACK => '退回',
        self::STATUS_TAKING => '揽件',
    ];

    /**
     * @param string $no 例如: SF:123456
     * @param string $company 物流公司必填
     * @param number $senderPhone 发送者手机号码
     * @return LogisticsForm
     * @throws NotFoundHttpException
     */
    public function query($no, $company, $senderPhone)
    {
        $curl = new Curl();
        $request = $curl->setGetParams([
            'key' => Yii::$app->services->config->backendConfig('logistics_juhe_app_code'),
            'com' => $company, // 需要查询的快递公司编号
            'no' => $no, // 需要查询的快递单号
            'senderPhone' => '',
            'receiverPhone' => ''
        ])->get(self::URL . '/index');
        $data = Json::decode($request);
        // 错误码
        if ($data['error_code'] != 0) {
            throw new NotFoundHttpException($data['reason']);
        }

        $form = new LogisticsForm();
        $form->no = $no;
        $form->company = $company;
        $form->code = $data['status'];
        $form->message = $data['msg'];
        $form->original = $data['result']['list'];
        if (!empty($form->original)) {
            foreach ($form->original as $item) {
                $form->list[] = [
                    'datetime' => $item['time'],
                    'remark' => $item['status'],
                    'zone' => '',
                ];
            }
        }

        return $form;
    }

    /**
     * 获取物流公司列表
     *
     * @return mixed|null
     * @throws \Exception
     */
    public function companies()
    {
        $curl = new Curl();
        $request = $curl->setGetParams([
            'key' => Yii::$app->services->config->backendConfig('logistics_juhe_app_code')
        ])->get(self::URL . '/com');
        $data = Json::decode($request);
        // 错误码
        if ($data['error_code'] != 0) {
            throw new NotFoundHttpException($data['reason']);
        }

        return $data['result'];
    }
}
