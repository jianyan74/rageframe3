<?php

namespace services\extend\logistics;

use Yii;
use linslin\yii2\curl\Curl;
use common\forms\LogisticsForm;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

/**
 * 阿里云物流查询
 *
 * Class ALiYunService
 * @package services\extend\logistics
 * @doc https://market.aliyun.com/products/57126001/cmapi021863.html
 * @author jianyan74 <751393839@qq.com>
 */
class ALiYunService
{
    const URL = 'https://wuliu.market.alicloudapi.com';

    const SUCCESS_STATUS = 0;
    const STATUS_ERROR = -1;
    const STATUS_COURIER_RECEIPT = 0;
    const STATUS_ON_THE_WAY = 1;
    const STATUS_SENDING_A_PIECE = 2;
    const STATUS_SIGNED = 3;
    const STATUS_DELIVERY_FAILED = 4;
    const STATUS_TROUBLESOME = 5;
    const STATUS_RETURN_RECEIPT = 6;

    /**
     * @var string[]
     */
    public $statusMap = [
        self::STATUS_ERROR => '异常',
        self::STATUS_COURIER_RECEIPT => '快递收件(揽件)',
        self::STATUS_ON_THE_WAY => '在途中',
        self::STATUS_SENDING_A_PIECE => '正在派件',
        self::STATUS_SIGNED => '已签收',
        self::STATUS_DELIVERY_FAILED => '派送失败',
        self::STATUS_TROUBLESOME => '疑难件',
        self::STATUS_RETURN_RECEIPT => '退件签收',
    ];

    /**
     * @param string $no 例如: SF:123456:2563
     * @param string $company
     * @param null $customerName 占位
     * @return LogisticsForm
     * @throws NotFoundHttpException
     */
    public function query($no, $company = null, $customerName = null)
    {
        $curl = new Curl();
        $request = $curl->setHeaders([
            'Authorization' => 'APPCODE ' . Yii::$app->services->config->backendConfig('logistics_aliyun_app_code')
        ])->setGetParams([
            'no' => $no,
            'type' => $company,
        ])->get(self::URL . '/kdi');
        $data = Json::decode($request);
        // 错误码
        if ($data['status'] != 0) {
            throw new NotFoundHttpException($data['msg']);
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
        $request = $curl->setHeaders([
            'Authorization' => 'APPCODE ' . Yii::$app->services->config->backendConfig('logistics_aliyun_app_code')
        ])->get(self::URL . '/getExpressList');
        $data = Json::decode($request);
        // 错误码
        if ($data['status'] != 200) {
            throw new NotFoundHttpException($data['msg']);
        }

        return $data['result'];
    }
}
