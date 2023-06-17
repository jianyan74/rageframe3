<?php

namespace services\extend\logistics;

use Yii;
use Exception;
use common\forms\LogisticsForm;
use linslin\yii2\curl\Curl;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

/**
 * Class KdnService
 * @package services\extend\logistics
 * @author jianyan74 <751393839@qq.com>
 */
class KdnService
{
    const URL = 'https://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx';
    const Sand_Box_URL = 'http://sandboxapi.kdniao.com:8080/kdniaosandbox/gateway/exterfaceInvoke.json';

    const KDNIAO_DATA_TYPE = 2;

    const SUCCESS_STATUS = 200;
    const STATUS_NO_TRACK = 0;
    const STATUS_PACKAGE = 1;
    const STATUS_ON_THE_WAY = 2;
    const STATUS_SIGNING = 3;
    const STATUS_QUESTION_PACKAGE = 4;
    const STATUS_IN_THE_CITY = 201;
    const STATUS_IN_THE_PACKAGE = 202;
    const STATUS_DIEPOSIT_ARK = 211;
    const STATUS_NORMAL_SIGNING = 301;
    const STATUS_ABNORMAL_SIGNING = 302;
    const STATUS_ISSUING_SIGNING = 304;
    const STATUS_ARK_SIGNING = 311;
    const STATUS_NO_DELIVERY_INFO = 401;
    const STATUS_TIMEOUT_NOT_SIGNING = 402;
    const STATUS_TIMEOUT_NOT_UPDATE = 403;
    const STATUS_RETURN_PACKAGE = 404;
    const STATUS_PACKAGE_ERROR = 405;
    const STATUS_RETURN_SINGNING = 406;
    const STATUS_RETURN_NOT_SINGNING = 407;
    const STATUS_ARK_NOT_SINGNING = 412;

    /**
     * @var string[]
     */
    public $statusMap = [
        self::STATUS_NO_TRACK => '无轨迹',
        self::STATUS_PACKAGE => '已揽收',
        self::STATUS_SIGNING => '已签收',
        self::STATUS_ON_THE_WAY => '在途中',
        self::STATUS_QUESTION_PACKAGE => '问题件',
        self::STATUS_IN_THE_CITY => '到达派件城市',
        self::STATUS_IN_THE_PACKAGE => '派件中',
        self::STATUS_DIEPOSIT_ARK => '已放入快递柜或驿站',
        self::STATUS_NORMAL_SIGNING => '正常签收',
        self::STATUS_ABNORMAL_SIGNING => '派件异常后最终签收',
        self::STATUS_ISSUING_SIGNING => '代收签收',
        self::STATUS_ARK_SIGNING => '快递柜或驿站签收',
        self::STATUS_NO_DELIVERY_INFO => '发货无信息',
        self::STATUS_TIMEOUT_NOT_SIGNING => '超时未签收',
        self::STATUS_TIMEOUT_NOT_UPDATE => '超时未更新',
        self::STATUS_RETURN_PACKAGE => '拒收(退件)',
        self::STATUS_PACKAGE_ERROR => '派件异常',
        self::STATUS_RETURN_SINGNING => '退货签收',
        self::STATUS_RETURN_NOT_SINGNING => '退货未签收',
        self::STATUS_ARK_NOT_SINGNING => '快递柜或驿站超时未取',
    ];

    /**
     * @param $no
     * @param $company
     * @param int $customerName ShipperCode 为 SF (顺丰) 时必填;对应寄件人/收件人手机号后四位
     * @return LogisticsForm
     * @throws NotFoundHttpException
     */
    public function query($no, $company = null, $customerName = null)
    {
        // 无物流公司，自动匹配物流
        if (empty($company)) {
            $companies = $this->autoCompanies([
                'LogisticCode' => $no,
            ]);
            $company = $companies['Shippers'][0]['ShipperCode'];
        }

        $params = [
            'LogisticCode' => $no,
            'ShipperCode' => $company,
            'CustomerName' => $customerName,
        ];

        $curl = new Curl();
        $request = $curl->setHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
        ])->setPostParams($this->getRequestParams($params, 1002))->post(self::URL);
        $data = Json::decode($request);
        if ($data['Success'] == false) {
            throw new NotFoundHttpException($data['Reason']);
        }

        $form = new LogisticsForm();
        $form->no = $no;
        $form->company = $company;
        $form->code = 200;
        $form->message = 'ok';
        $form->original = $data['Traces'];
        if (!empty($form->original)) {
            foreach ($form->original as $item) {
                $form->list[] = [
                    'datetime' => $item['AcceptTime'],
                    'remark' => $item['AcceptStation'],
                    'zone' => $item['Remark'],
                ];
            }
        }

        return $form;
    }

    /**
     * 获取物流公司列表
     *
     * @return mixed|null
     * @throws Exception
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
    public function autoCompanies($params)
    {
        $curl = new Curl();
        $request = $curl->setHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
        ])->setGetParams($this->getRequestParams($params, 2002))->get(self::URL);
        $data = Json::decode($request);
        if ($data['Success'] == false) {
            throw new NotFoundHttpException($data['Reason']);
        }

        // 错误码
        if (empty($data) || empty($data['Shippers'][0]['ShipperCode'])) {
            throw new NotFoundHttpException('未查询到该订单信息!');
        }

        return $data;
    }

    /**
     * @param $requestData
     * @param $requestType
     *
     * @return array
     */
    private function getRequestParams($requestData, $requestType)
    {
        return [
            'EBusinessID' => trim(Yii::$app->services->config->backendConfig('logistics_kdniao_app_id')),
            'DataType' => self::KDNIAO_DATA_TYPE,
            'RequestType' => $requestType,
            'RequestData' => urlencode(Json::encode($requestData)),
            'DataSign' => $this->generateSign($requestData, trim(Yii::$app->services->config->backendConfig('logistics_kdniao_app_key'))),
        ];
    }

    /**
     * @param $param
     * @param $key
     *
     * @return string
     */
    protected function generateSign($param, $key)
    {
        return urlencode(base64_encode(md5(Json::encode($param).$key)));
    }
}
