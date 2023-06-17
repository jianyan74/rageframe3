<?php

namespace services\extend\printer;

use common\components\Service;
use common\helpers\StringHelper;
use common\models\extend\printer\XpYun;
use linslin\yii2\curl\Curl;
use Yii;
use yii\helpers\Json;
use yii\web\UnprocessableEntityHttpException;
use function services\extend\iconv;
use function services\extend\mb_strlen;
use function services\extend\mb_strwidth;
use function services\extend\mb_substr;

/**
 * 芯烨云打印机
 *
 * Class XpYunService
 * @package services\extend
 * @author jianyan74 <751393839@qq.com>
 */
class XpYunService extends Service
{
    const URL = 'https://open.xpyun.net/api/openapi/'; //接口IP或域名

    public $terminal_number;
    public $app_id;
    public $app_secret_key;
    public $print_num = 1;

    /**
     * @var string[]
     */
    protected $errorInfo = [
        0 => '成功',
        -1 => '请求头错误',
        -2 => '参数不合法',
        -3 => '参数签名失败',
        -4 => '用户未注册',
        1001 => '打印机编号和用户不匹配',
        1002 => '打印机未注册',
        1003 => '打印机不在线',
        1004 => '添加订单失败',
        1005 => '未找到订单信息',
        1006 => '订单日期格式或大小不正确',
        1007 => '打印内容不能超过12K',
        1008 => '用户修改打印机记录失败',
        1009 => '用户添加打印机时，打印机编号或名称不能为空',
        1010 => '打印机设备编号无效',
        1011 => '打印机已存在，若当前开放平台无法查询到打印机信息，请联系售后技术支持人员核实',
        1012 => '添加打印设备失败，请稍后再试或联系售后技术支持人员',
    ];

    /**
     * @param XpYun $config
     */
    public function initConfig(XpYun $config)
    {
        $this->app_id = $config->app_id;
        $this->app_secret_key = $config->app_secret_key;
        $this->terminal_number = $config->terminal_number;
        $this->print_num = $config->print_num;

        parent::init();
    }

    /**
     * @param $content
     * @param int $times
     * @param XpYun|array $config
     */
    public function print($content, $config = [])
    {
        if (!empty($config)) {
            $this->initConfig($config);
        }

        // 格式化内容
        $content = $this->formattedContent($content);

        $time = time(); // 请求时间
        $jsonStr = [
            'user' => $this->app_id,
            'timestamp' => $time,
            'sign' => $this->signature($time),
            'debug' => '1',
            'sn' => $this->terminal_number,
            'content' => $content,
            'mode' => '1', // 值为 1 不检查打印机是否在线，直接生成打印订单，并返回打印订单号。如果打印机不在线，订单将缓存在打印队列中，打印机正常在线时会自动打印
            'copies' => $this->print_num, // 打印次数
            'money' => $content['payMoney'] ?? 0
        ];

        $jsonStr = Json::encode($jsonStr);

        $curl = new Curl();
        $result = $curl->setHeaders([
            'Content-Type' => 'application/json;charset=UTF-8',
            'Content-Length: ' . strlen($jsonStr)
        ])->setRawPostData($jsonStr)->post(self::URL . 'xprinter/print');

        $result = Json::decode($result);
        if ($result['code'] != 0) {
            if (isset($this->errorInfo[$result['code']])) {
                throw new UnprocessableEntityHttpException($this->errorInfo[$result['code']]);
            }

            throw new UnprocessableEntityHttpException($result['msg']);
        }

        return $result['data'];
    }

    /**
     * @param $data
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    protected function formattedContent($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        $content = "<BOLD><C>****** {$data['title']} ******<BR></C></BOLD>" . "<BR>";
        $content .= str_repeat('.', 32) . "<BR>";
        $content .= "<BOLD><C>---{$data['payType']}---<BR></C></BOLD>" . "<BR>";
        // $content .= "<C>{$data['merchantTitle']}</C>";
        $content .= "打印时间:" . Yii::$app->formatter->asDatetime(time()) . "<BR>";
        $content .= "下单时间:" . $data['orderTime'] . "<BR>";
        $content .= str_repeat('*', 13) . " 商品 " . str_repeat("*", 13) . "<BR>";
        $content .= $this->composing($data['products']);

        $content .= "商品总价：￥{$data['productOriginalMoney']}<BR>";
        foreach ($data['marketingDetails'] as $marketingDetail) {
            $content .= "{$marketingDetail['marketing_name']}：-￥{$marketingDetail['discount_money']}<BR>";
        }
        $data['pointMoney'] > 0 && $content .= "积分抵扣：-￥{$data['pointMoney']}<BR>";
        $data['taxMoney'] > 0 && $content .= "发票税额：-￥{$data['taxMoney']}<BR>";
        $content .= "配送费：￥{$data['shippingMoney']}<BR>";
        $content .= "应付金额：￥{$data['payMoney']}<BR>";

        $content .= str_repeat('.', 32) . "<BR>";
        $content .= "昵称: " . $data['nickname'] . "<BR>";
        isset($data['receiverName']) && $content .= "客户: " . $data['receiverName'] . "<BR>";
        isset($data['receiverMobile']) && $content .= "电话: " . StringHelper::hideStr($data['receiverMobile'], 3) . "<BR>";
        isset($data['receiverAddress']) && $content .= "地址: " . $data['receiverRegionName'] . $data['receiverAddress'] . "<BR>";
        isset($data['buyerMessage']) && $content .= "备注: " . !empty($data['buyerMessage']) ? $data['buyerMessage'] : '无' . "<BR>";
        if (!empty($data['qr'])) {
            $content .= str_repeat('.', 32) . "<BR>";
            $content .= "<C>二维码<BR></C>";
            $content .= "<C><QR>{$data['qr']}</QR></C>";
        } else {
            $content .= str_repeat('.', 32) . "<BR>";
            $content .= "<C>二维码<BR></C>";
            $content .= "<C><QR>{$data['orderSn']}</QR></C>";
        }

        $content .= str_repeat('.', 32) . "<BR>";
        $content .= "<C>订单号</C>";
        $content .= "<C><BARCODE>{$data['orderSn']}</BARCODE></C>" . "<BR>";
        $content .= "<BOLD><C>****** 完 ******<BR></C></BOLD>" . "<BR>";

        return $content;
    }

    /**
     *
     * @param $products
     * @param int $A 名称
     * @param int $B 单价
     * @param int $C 数量
     * @param int $D 金额
     * @return string
     */
    protected function composing($products, $A = 14, $B = 6, $C = 3, $D = 6)
    {
        $orderInfo = '商品名称　　　　　   数量 金额<BR>';
        $orderInfo .= '--------------------------------<BR>';
        foreach ($products as $k5 => $v5) {
            $name = $v5['title'];
            // $price = $v5['price'];
            $price = '';
            $num = $v5['num'];
            $prices = $v5['price'];
            $kw3 = '';
            $kw1 = '';
            $kw2 = '';
            $kw4 = '';
            $str = $name;
            $blankNum = $A;//名称控制为14个字节
            $lan = mb_strlen($str, 'utf-8');
            $m = 0;
            $j = 1;
            $blankNum++;
            $result = array();
            if (strlen($price) < $B) {
                $k1 = $B - strlen($price);
                for ($q = 0; $q < $k1; $q++) {
                    $kw1 .= ' ';
                }
                $price = $price . $kw1;
            }
            if (strlen($num) < $C) {
                $k2 = $C - strlen($num);
                for ($q = 0; $q < $k2; $q++) {
                    $kw2 .= ' ';
                }
                $num = $num . $kw2;
            }
            if (strlen($prices) < $D) {
                $k3 = $D - strlen($prices);
                for ($q = 0; $q < $k3; $q++) {
                    $kw4 .= ' ';
                }
                $prices = $prices . $kw4;
            }
            for ($i = 0; $i < $lan; $i++) {
                $new = mb_substr($str, $m, $j, 'utf-8');
                $j++;
                if (mb_strwidth($new, 'utf-8') < $blankNum) {
                    if ($m + $j > $lan) {
                        $m = $m + $j;
                        $tail = $new;
                        $lenght = iconv("UTF-8", "GBK//IGNORE", $new);
                        $k = $A - strlen($lenght);
                        for ($q = 0; $q < $k; $q++) {
                            $kw3 .= ' ';
                        }
                        if ($m == $j) {
                            $tail .= $kw3 . ' ' . $price . ' ' . $num . ' ' . $prices;
                        } else {
                            $tail .= $kw3 . '<BR>';
                        }
                        break;
                    } else {
                        $next_new = mb_substr($str, $m, $j, 'utf-8');
                        if (mb_strwidth($next_new, 'utf-8') < $blankNum) {
                            continue;
                        } else {
                            $m = $i + 1;
                            $result[] = $new;
                            $j = 1;
                        }
                    }
                }
            }
            $head = '';
            foreach ($result as $key => $value) {
                if ($key < 1) {
                    $v_lenght = iconv("UTF-8", "GBK//IGNORE", $value);
                    $v_lenght = strlen($v_lenght);
                    if ($v_lenght == 13) {
                        $value = $value . " ";
                    }
                    $head .= $value . ' ' . $price . ' ' . $num . ' ' . $prices;
                } else {
                    $head .= $value . '<BR>';
                }
            }
            $orderInfo .= $head . $tail;
        }

        $orderInfo .= '--------------------------------<BR>';

        return $orderInfo;
    }

    /**
     * @param $time
     * @return string
     */
    protected function signature($time)
    {
        // 公共参数，请求公钥
        return sha1($this->app_id . $this->app_secret_key . $time);
    }
}
