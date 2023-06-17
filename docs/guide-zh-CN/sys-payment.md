## 公用支付

目录

- 支付宝
- 微信
- 银联

### 支付宝

订单

```
// 配置
$config = [
    'notify_url' => 'http://rageframe.com/notify.php', // 支付通知回调地址
    'return_url' => 'http://rageframe.com/return.php', // 买家付款成功跳转地址
    'mode' => 0, // 0:普通模式; 1:沙盒模式; 2:服务商模式
];

// 生成订单
$order = [
    'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
    'total_amount' => 0.01,
    'subject'      => 'test',
];
```

生成参数

```
// 电脑网站支付
$resConfig = Yii::$app->pay->alipay->web($order);

// 手机网站支付
$resConfig = Yii::$app->pay->alipay->wap($order);

// app支付
$resConfig = Yii::$app->pay->alipay->app($order);

// 小程序支付
$order['buyer_id'] = '2088622190161234';
$resConfig = Yii::$app->pay->alipay->mini($order);

// 扫码支付
$resConfig = Yii::$app->pay->alipay->sacn($order);

// 刷卡支付（被扫码）
$order['auth_code'] = '284776044441477959';
$resConfig = Yii::$app->pay->alipay->pos($order);
```

退款

```
$info = [
      'out_trade_no' => 'The existing Order ID',
      'trade_no' => 'The Transaction ID received in the previous request',
      'refund_amount' => 18.4,
      'out_request_no' => date('YmdHis') . mt_rand(1000, 9999)
];
 
   
Yii::$app->pay->alipay->refund($info); 
```

异步/同步通知

```
$request = Yii::$app->pay->alipay->callback()

// 成功
return Yii::$app->pay->alipay->success();
```

单笔转账

```
$info = [
     'out_biz_no' => '转账单号',
     'trans_amount' => '收款金额',
     'payee_info' => [
          'identity_type' => 'ALIPAY_LOGON_ID', // ALIPAY_USER_ID:支付宝唯一号;ALIPAY_LOGON_ID:支付宝登录号
          'identity' => '收款人账号',
          'name' => '收款方真实姓名', // 非必填
     ],
     'remark' => '账业务的标题，用于在支付宝用户的账单里显示', // 非必填
     'order_title' => '转账业务的标题，用于在支付宝用户的账单里显示 ', // 非必填
  ]
  
return Yii::$app->pay->alipay->transfer($info);
```

转账查询

```
查询普通支付订单
$order = [
    'out_trade_no' => '1514027114',
];
// $order = '1514027114';

$result = Yii::$app->pay->alipay->find($order);

查询退款订单
$order = [
    'out_trade_no' => '1514027114',
    'out_request_no' => '1514027114',
    '_type' => 'refund',
];

$result = Yii::$app->pay->alipay->find($order);

$order = [
    'out_biz_no' => '202209032319',
    '_type' => 'transfer'
];

$result = Yii::$app->pay->alipay->find($order);
```

单笔转账文档：https://opendocs.alipay.com/apis/api_28/alipay.fund.trans.toaccount.transfer

### 微信

订单生成参数

```
// 公众号支付
$order = [
    'out_trade_no' => time().'',
    'description' => 'subject-测试',
    'amount' => [
        'total' => 1,
    ],
    'payer' => [
        'openid' => 'onkVf1FjWS5SBxxxxxxxx',
    ],
];
$resConfig = Yii::$app->pay->wechat->mp($order);

// 小程序支付
$order = [
    'out_trade_no' => time().'',
    'description' => 'subject-测试',
    'amount' => [
        'total' => 1,
        'currency' => 'CNY',
    ],
    'payer' => [
        'openid' => '123fsdf234',
    ]
];
$resConfig = Yii::$app->pay->wechat->mini($order);

// 原生扫码支付(二维码)
$order = [
    'out_trade_no' => time().'',
    'description' => 'subject-测试',
    'amount' => [
        'total' => 1,
    ],
];
$resConfig = Yii::$app->pay->wechat->sacn($order);

// app支付
$order = [
    'out_trade_no' => time().'',
    'description' => 'subject-测试',
    'amount' => [
        'total' => 1,
    ],
];
$resConfig = Yii::$app->pay->wechat->app($order);

// 刷卡支付(暂时不支持)
$resConfig = Yii::$app->pay->wechat->pos($order);

// H5支付(非微信内)
$order = [
    'out_trade_no' => time().'',
    'description' => 'subject-测试',
    'amount' => [
        'total' => 1,
    ],
    'scene_info' => [
        'payer_client_ip' => '1.2.4.8',
        'h5_info' => [
            'type' => 'Wap',
        ]       
    ],
];
$resConfig = Yii::$app->pay->wechat->wap($order);
```

回调

```
$result = Yii::$app->pay->wechat->callback();

return Yii::$app->pay->wechat->success();
```

关闭订单

```
$order = [
    'out_trade_no' => '1514027114',
];

// $order = '1514027114';

$response = Yii::$app->pay->wechat->close($order);
```

查询订单

```
$order = [
    'transaction_id' => '1217752501201407033233368018',
];
// $order = '1217752501201407033233368018';

$response = Yii::$app->pay->wechat->find($order);
```

退款

```
$order = [
    'out_trade_no' => '1514192025',
    'out_refund_no' => time(),
    'amount' => [
        'refund' => 1,
        'total' => 1,
        'currency' => 'CNY',
    ],
];

$response = Yii::$app->pay->wechat->refund($order);
```

转账

```
$order = [
    'out_batch_no' => time().'',
    'batch_name' => 'subject-测试',
    'batch_remark' => 'test',
    'total_amount' => 1,
    'total_num' => 1,
    'transfer_detail_list' => [
        [
            'out_detail_no' => time().'-1',
            'transfer_amount' => 1,
            'transfer_remark' => 'test',
            'openid' => 'MYE42l80oelYMDE34nYD456Xoy',
            // 'user_name' => '闫嵩达'  // 明文传参即可，sdk 会自动加密
        ],
    ],
];

$response = Yii::$app->pay->wechat->transfer($order);
```

### 银联

订单

```
// 配置
$config = [
    'notify_url' => 'http://rageframe.com/notify.php', // 支付通知回调地址
    'return_url' => 'http://rageframe.com/return.php', // 买家付款成功跳转地址
];

// 生成订单
$order = [
    'orderId'   => date('YmdHis'), //Your order ID
    'txnTime'   => date('YmdHis'), //Should be format 'YmdHis'
    'orderDesc' => 'My order title', // Order Title
    'txnAmt'    => '100', //Order Total Fee
];
```

生成参数

```
// web
$resConfig = Yii::$app->pay->unipay($config)->web($order);

// wap
$resConfig = Yii::$app->pay->unipay($config)->wap($order);

// scan 扫码支付（主扫码） 
$resConfig = Yii::$app->pay->unipay($config)->scan($order);

// pos 刷卡支付（被扫码） 
$resConfig = Yii::$app->pay->unipay($config)->pos($order);
```

回调

```
$response = Yii::$app->pay->unipay->callback();
```

查询订单

```
$info = [
  'orderId' => '转账单号',
  'txnTime' => date('YmdHis') // 时间
]

or

$info = [
  'orderId' => '转账单号',
  'txnTime' => date('YmdHis') // 时间
  '_type' => 'qr_code', // 查询二维码支付订单
]

$response = Yii::$app->pay->unipay->find($info);
```

关闭订单

```
$order = [
    'txnTime' => date('YmdHis'),
    'txnAmt' => 1,
    'orderId' => 'cancel'.date('YmdHis'),
    'origQryId' => '062209121414535249018'
];

$response = Yii::$app->pay->unipay->cancel($order);
```

退款

```
$order = [
    'txnTime' => date('YmdHis'),
    'txnAmt' => 1,
    'orderId' => 'refund'.date('YmdHis'),
    'origQryId' => '392209121420295251518'
];

二维码退款

$order = [
    'txnTime' => date('YmdHis'),
    'txnAmt' => 1,
    'orderId' => 'refund'.date('YmdHis'),
    'origQryId' => '392209121420295251518',
    '_type' => 'qr_code',
];

$response = Yii::$app->pay->unipay->refund($info);
```
