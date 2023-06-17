<?php

namespace services\extend\logistics;

use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use common\components\Service;
use common\forms\LogisticsForm;
use linslin\yii2\curl\Curl;

/**
 * Class Kd100Service
 * @package services\extend\logistics
 * @author jianyan74 <751393839@qq.com>
 */
class Kd100Service extends Service
{
    const SUCCESS_STATUS = 200;
    const STATUS_ON_THE_WAY = 0;
    const STATUS_PACKAGE = 1;
    const STATUS_DIFFICULT = 2;
    const STATUS_SIGNING = 3;
    const STATUS_REFUND = 4;
    const STATUS_PIECE = 5;
    const STATUS_RETURN = 6;
    const RETURN_TO_BE_CLEARED = 10;
    const STATUS_CLEARANCE = 11;
    const STATUS_CLEARED = 12;
    const STATUS_CUSTOMS_CLEARANCE_ABNORMALITY = 13;
    const STATUS_RECIPIENT_REFUSAL = 14;

    /**
     * @var string[]
     */
    public $statusMap = [
        self::STATUS_PACKAGE => '揽件',
        self::STATUS_DIFFICULT => '疑难',
        self::STATUS_SIGNING => '签收',
        self::STATUS_REFUND => '退签',
        self::STATUS_PIECE => '派件',
        self::STATUS_RETURN => '退回',
        self::RETURN_TO_BE_CLEARED => '待清关',
        self::STATUS_CLEARANCE => '清关中',
        self::STATUS_CLEARED => '已清关',
        self::STATUS_CUSTOMS_CLEARANCE_ABNORMALITY => '清关异常',
        self::STATUS_RECIPIENT_REFUSAL => '收件人拒签',
    ];

    /**
     * @param $no
     * @param $company
     * @param $phone
     * @return LogisticsForm
     * @throws NotFoundHttpException
     */
    public function query($no, $company = null, $phone = null)
    {
        $key = Yii::$app->services->config->backendConfig('logistics_kd100_app_key');
        $customer = Yii::$app->services->config->backendConfig('logistics_kd100_app_id');
        $customer = strtolower($customer);
        // 无物流公司，自动匹配物流
        if (empty($company)) {
            $companies = $this->autoCompanies($key, $no);
            $company = current($companies)['comCode'];
        }

        $param = [
            'customer' => $customer,
            'com' => $company,
            'num' => $no,
            'phone' => $phone, // 收、寄件人的电话号码（手机和固定电话均可，只能填写一个，顺丰速运和丰网速运必填，其他快递公司选填。如座机号码有分机号，分机号无需传入。）
        ];

        $param = Json::encode($param);

        $curl = new Curl();
        $request = $curl->setHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->setPostParams([
            'param' => $param,
            'customer' => $customer,
            'sign' => $this->generateSign($param, $key, $customer),
        ])->post('http://poll.kuaidi100.com/poll/query.do');
        $data = Json::decode($request);
        // 错误码
        if (isset($data['returnCode']) && 200 != $data['returnCode']) {
            throw new NotFoundHttpException($data['message']);
        }

        $form = new LogisticsForm();
        $form->no = $no;
        $form->company = $company;
        $form->code = $data['status'];
        $form->message = $data['message'];
        $form->original = $data['data'];
        if (!empty($form->original)) {
            foreach ($form->original as $item) {
                $form->list[] = [
                    'datetime' => $item['time'],
                    'remark' => $item['context'],
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
        return [];
    }

    /**
     * 自动匹配单号
     *
     * @param $no
     * @return mixed|null
     * @throws NotFoundHttpException
     */
    public function autoCompanies($key, $no)
    {
        $curl = new Curl();
        $request = $curl->setHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->setGetParams([
            'key' => $key,
            'num' => $no,
        ])->get('http://www.kuaidi100.com/autonumber/auto');
        $data = Json::decode($request);
        // 错误码
        if (isset($data['returnCode']) && self::SUCCESS_STATUS != $data['returnCode']) {
            throw new NotFoundHttpException($data['message']);
        }

        if (empty($data)) {
            throw new NotFoundHttpException('未查询到该订单信息!');
        }

        return $data;
    }

    /**
     * @param $param
     * @param $key
     * @param $customer
     *
     * @return string
     */
    protected function generateSign($param, $key, $customer)
    {
        return strtoupper(md5($param . $key . $customer));
    }
}
