<?php

namespace services\extend\printer;

use common\helpers\StringHelper;
use Yii;
use yii\helpers\Json;
use function services\extend\mb_strlen;
use function services\extend\mb_substr;

/**
 * 本地打印
 *
 * 官网：http://hiprint.io/
 *
 * Class HiPrintService
 * @package services\extend
 * @author jianyan74 <751393839@qq.com>
 */
class HiPrintService
{
    /**
     * 58 纸张
     *
     * @param $data
     * @return \array[][]|mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function textByFiftyEight($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        $width = 659;
        $fontSize = 43;
        $num = 0;

        $content = [];
        $content[] = " ";
        $content[] = "********** {$data['title']} **********";
        $content[] =  str_repeat('.', 28);
        $content[] =  "--- {$data['payType']} ---";
        $content[] =  [
            'options' => [
                'title' => "打印时间: " . Yii::$app->formatter->asDatetime(time()),
                'fontSize' => 35
            ]
        ];
        $content[] =  [
            'options' => [
                'title' => "下单时间: " . $data['orderTime'],
                'fontSize' => 35,
            ]
        ];

        $content[] =  str_repeat('*', 11) . " 商品 " . str_repeat("*", 11);
        $productTable = [
            [
                ['title' => '商品名称'],
                ['title' => '数量'],
                ['title' => '金额'],
            ]
        ];

        foreach ($data['products'] as $product) {
            list($tmpMum, $title) = $this->effectiveLen($product['title']);
            $num += $tmpMum;
            $productTable[] = [
                ['title' => $title],
                ['title' => 'x' . $product['num']],
                ['title' => '￥' . $product['price']],
            ];
        }

        $content[] =  [
            'options' => [
                'columns' => $productTable,
                'textAlign' => 'center',
                'tableBorder' => 'noBorder',
                'tableHeaderBorder' => 'noBorder',
                'tableHeaderCellBorder' => 'noBorder',
                'tableBodyRowBorder' => 'noBorder',
                'height' => count($productTable) * 80
            ],
            'printElementType' => [
                'type' => 'tableCustom',
                'title' => '表格',
            ]
        ];

        $content[] =  str_repeat('*', 28);

        // 价格
        $priceTable = [
            [
                ['title' => '商品总价'],
                ['title' => '￥' . $data['productOriginalMoney']]
            ]
        ];

        foreach ($data['marketingDetails'] as $marketingDetail) {
            $priceTable[] = [
                ['title' => $marketingDetail['marketing_name'],],
                ['title' => '-￥' . $marketingDetail['discount_money'],],
            ];
        }

        if ($data['pointMoney'] > 0) {
            $priceTable[] = [
                ['title' => '积分抵扣'],
                ['title' => '￥' . $data['pointMoney']]
            ];
        }

        if ($data['taxMoney'] > 0) {
            $priceTable[] = [
                ['title' => '发票税额'],
                ['title' => '￥' . $data['taxMoney']]
            ];
        }

        $priceTable[] = [
            ['title' => '配送费'],
            ['title' => '￥' . $data['shippingMoney']]
        ];

        $priceTable[] = [
            ['title' => '应付金额'],
            ['title' => '￥' . $data['payMoney']],
        ];

        $content[] =  [
            'options' => [
                'columns' => $priceTable,
                'tableBorder' => 'noBorder',
                'tableHeaderBorder' => 'noBorder',
                'tableHeaderCellBorder' => 'noBorder',
                'tableBodyRowBorder' => 'noBorder',
                'left' => 0,
                'textAlign' => 'right',
                'height' => count($priceTable) * 50
            ],
            'printElementType' => [
                'type' => 'tableCustom',
                'title' => '表格'
            ]
        ];

        $content[] =  str_repeat('.', 28);
        $content[] =  [
            'options' => [
                'title' => "昵称: " . $data['nickname'],
                'fontSize' => 35
            ]
        ];

        isset($data['receiverName']) && $content[] = [
            'options' => [
                'title' => "客户: " . $data['receiverName'],
                'fontSize' => 35
            ]
        ];

        isset($data['receiverMobile']) && $content[] = [
            'options' => [
                'title' => "电话: " . StringHelper::hideStr($data['receiverMobile'], 3),
                'fontSize' => 35
            ]
        ];

        isset($data['receiverAddress']) && $content[] = [
            'options' => [
                'title' => "地址: " . $data['receiverRegionName'] . $data['receiverAddress'],
                'fontSize' => 35
            ]
        ];

        isset($data['buyerMessage']) && $content[] = [
            'options' => [
                'title' => "备注: " . (!empty($data['buyerMessage']) ? $data['buyerMessage'] : '无'),
                'fontSize' => 35
            ]
        ];

        $content[] = str_repeat('.', 28);
        $content[] = "二维码";
        if (!empty($data['qr'])) {
            $content[] = [
                'options' => [
                    'title' => $data['qr'],
                    'textType' => 'qrcode',
                    'textAlign' => 'center',
                    'left' => 129,
                    'height' => 200,
                    'width' => 210,
                ]
            ];
        } else {
            $content[] = [
                'options' => [
                    'title' => $data['orderSn'],
                    'textType' => 'qrcode',
                    'textAlign' => 'center',
                    'left' => 129,
                    'height' => 400,
                    'width' => 400,
                ]
            ];
        }

        $content[] = str_repeat('.', 28);
        $content[] = "订单号";
        $content[] = [
            'options' => [
                'title' => $data['orderSn'],
                'textType' => 'barcode',
                'textAlign' => 'center',
                'fontSize' => $fontSize,
                'lineHeight' => 60,
                'left' => 77,
                'height' => 80,
                'width' => 500,
            ]
        ];

        $content[] = " ";
        $content[] = str_repeat('*', 12) . " 完 " . str_repeat('*', 12);

        $printElements = [];
        $top = 20;
        foreach ($content as $key => $item) {
            if (is_array($item)) {
                !isset($item['options']['top']) && $item['options']['top'] = $top;
                if (empty($item['options']['textAlign'])) {
                    !isset($item['options']['left']) && $item['options']['left'] = 30;
                }

                !isset($item['options']['width']) && $item['options']['width'] = $width;
                !isset($item['options']['height']) && $item['options']['height'] = 35;
                !isset($item['options']['lineHeight']) && $item['options']['lineHeight'] = 35;
                !isset($item['options']['fontWeight']) && $item['options']['fontWeight'] = $key == 0 ? 600 : 500;
                !isset($item['options']['fontSize']) && $item['options']['fontSize'] = $fontSize;
                !isset($item['printElementType']) && $item['printElementType'] = [
                    'title' => '文本',
                    'type' => 'text'
                ];
            } else {
                $item = [
                    'options' => [
                        'title' => $item,
                        'fontSize' => $fontSize,
                        'height' => 35,
                        'width' => $width,
                        'top' => $top,
                        'left' => 0,
                        'fontWeight' => $key == 0 ? 600 : 500,
                        'textAlign' => 'center',
                        'lineHeight' => 27,
                    ],
                    'printElementType' => [
                        'title' => '文本',
                        'type' => 'text'
                    ]
                ];
            }

            $top += $item['options']['height'] + 30;
            $printElements[] = $item;
        }

        return [
            'panels' => [
                [
                    'index' => '0',
                    'paperHeader' => 10,
                    'paperFooter' => 10,
                    'height' => (int)($top / 2.7) + $num * 10,
                    'width' => 233,
                    'printElements' => $printElements
                ]
            ]
        ];
    }

    /**
     * @param $string
     * @return array
     */
    protected function effectiveLen($string)
    {
        $len = strlen($string);
        if ($len < 24) {
            return [0, $string];
        }

        $letter = [];
        for ($i = 0; $i < mb_strlen($string, 'UTF-8'); $i++) {
            $letter[] = mb_substr($string, $i, 1, 'UTF-8');
        }

        $num = 0;
        foreach ($letter as $key => $value) {
            if ($key > 0 && ($key % 6) == 0) {
                $num++;
            }
        }

        return [$num, $string];
    }

    public function test()
    {
        $str = '{"panels":[{"index":0,"height":297,"width":210,"paperHeader":49.5,"paperFooter":780,"printElements":[{"options":{"left":175.5,"top":10.5,"height":27,"width":259,"title":"HiPrint自定义模块打印插件","fontSize":19,"fontWeight":"600","textAlign":"center","lineHeight":26},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":60,"top":27,"height":13,"width":52,"title":"页眉线","textAlign":"center"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":25.5,"top":57,"height":705,"width":9,"fixed":true,"borderStyle":"dotted"},"printElementType":{"type":"vline"}},{"options":{"left":60,"top":61.5,"height":48,"width":87,"src":"/Content/assets/hi.png"},"printElementType":{"title":"图片","type":"image"}},{"options":{"left":153,"top":64.5,"height":39,"width":276,"title":"二维码以及条形码均采用svg格式打印。不同打印机打印不会造成失真。图片打印：不同DPI打印可能会导致失真，","fontFamily":"微软雅黑","textAlign":"center","lineHeight":18},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":457.5,"top":79.5,"height":13,"width":120,"title":"姓名","field":"name","testData":"古力娜扎","color":"#f00808","textDecoration":"underline","textAlign":"center"},"printElementType":{"title":"文本","type":"text"}},{"options":{"left":499.5,"top":120,"height":43,"width":51,"title":"123456789","textType":"qrcode"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":285,"top":130.5,"height":34,"width":175,"title":"123456789","fontFamily":"微软雅黑","textAlign":"center","textType":"barcode"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":60,"top":132,"height":19,"width":213,"title":"所有打印元素都可已拖拽的方式来改变元素大小","fontFamily":"微软雅黑","textAlign":"center","lineHeight":18},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":153,"top":189,"height":13,"width":238,"title":"单击元素，右侧可自定义元素属性","textAlign":"center","fontFamily":"微软雅黑"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":60,"top":190.5,"height":13,"width":51,"title":"横线","textAlign":"center"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":415.5,"top":190.5,"height":13,"width":164,"title":"可以配置各属性的默认值","textAlign":"center","fontFamily":"微软雅黑"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":60,"top":214.5,"height":10,"width":475.5},"printElementType":{"title":"横线","type":"hline"}},{"options":{"left":235.5,"top":220.5,"height":32,"width":342,"title":"自定义表格：用户可左键选中表头，右键查看可操作项，操作类似Excel，双击表头单元格可进行编辑。内容：title#field","fontFamily":"微软雅黑","textAlign":"center","lineHeight":15},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":156,"top":265.5,"height":13,"width":94,"title":"表头列大小可拖动","fontFamily":"微软雅黑","textAlign":"center"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":60,"top":265.5,"height":13,"width":90,"title":"红色区域可拖动","fontFamily":"微软雅黑","textAlign":"center"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":60,"top":285,"height":44,"width":511.5,"field":"table","columns":[[{"width":85.25,"colspan":1,"rowspan":1,"checked":true},{"width":85.25,"colspan":1,"rowspan":1,"checked":true},{"title":"姓名","field":"name","width":85.25,"align":"center","colspan":1,"rowspan":1,"checked":true,"columnId":"name"},{"width":85.25,"colspan":1,"rowspan":1,"checked":true},{"width":85.25,"colspan":1,"rowspan":1,"checked":true},{"width":85.25,"colspan":1,"rowspan":1,"checked":true}]]},"printElementType":{"title":"表格","type":"tableCustom"}},{"options":{"left":21,"top":346.5,"height":61.5,"width":15,"title":"装订线","lineHeight":18,"fixed":true,"contentPaddingTop":3.75,"backgroundColor":"#ffffff"},"printElementType":{"type":"text"}},{"options":{"left":225,"top":349.5,"height":13,"width":346.5,"title":"自定义模块：主要为开发人员设计，能够快速，简单，实现自己功能","textAlign":"center"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":60,"top":370.5,"height":18,"width":79,"title":"配置项表格","textAlign":"center"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":225,"top":385.5,"height":38,"width":346.5,"title":"配置模块：主要为客户使用，开发人员可以配置属性，字段，标题等，客户直接使用，配置模块请参考实例2","fontFamily":"微软雅黑","lineHeight":15,"textAlign":"center","color":"#d93838"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":60,"top":487.5,"height":13,"width":123,"title":"长文本会自动分页","textAlign":"center"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":60,"top":507,"height":40,"width":511.5,"field":"longText"},"printElementType":{"title":"长文","type":"longText"}},{"options":{"left":475.5,"top":565.5,"height":100,"width":100},"printElementType":{"title":"矩形","type":"rect"}},{"options":{"left":174,"top":568.5,"height":13,"width":90,"title":"竖线","textAlign":"center"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":60,"top":574.5,"height":100,"width":10},"printElementType":{"title":"竖线","type":"vline"}},{"options":{"left":210,"top":604.5,"height":13,"width":120,"title":"横线","textAlign":"center"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":130.5,"top":625.5,"height":10,"width":277},"printElementType":{"title":"横线","type":"hline"}},{"options":{"left":364.5,"top":649.5,"height":13,"width":101,"title":"矩形","textAlign":"center"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":525,"top":784.5,"height":13,"width":63,"title":"页尾线","textAlign":"center"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":12,"top":786,"height":49,"width":49},"printElementType":{"title":"html","type":"html"}},{"options":{"left":75,"top":790.5,"height":13,"width":137,"title":"红色原型是自动定义的Html","textAlign":"center"},"printElementType":{"title":"自定义文本","type":"text"}},{"options":{"left":334.5,"top":810,"height":13,"width":205,"title":"页眉线已上。页尾下以下每页都会重复打印","textAlign":"center"},"printElementType":{"title":"自定义文本","type":"text"}}],"paperNumberLeft":565.5,"paperNumberTop":819}]}';

        return Json::decode($str);
    }
}
