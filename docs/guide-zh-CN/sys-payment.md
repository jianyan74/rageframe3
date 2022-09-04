## 公用支付

目录

- 支付宝
- 微信
- 银联(弃用)

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

// app支付
$resConfig = Yii::$app->pay->alipay->app($order);

// 面对面支付(创建收款二维码)
$resConfig = Yii::$app->pay->alipay->sacn($order);

// 手机网站支付
$resConfig = Yii::$app->pay->alipay->wap($order);
```

扫码收款

```
$request = Yii::$app->pay->alipay->pos([
    'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
    'scene'        => 'bar_code',
    'auth_code'    => '288412621343841260',  //购买者手机上的付款二维码
    'subject'      => 'test',
    'total_amount' => 0.01,
]);
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
return Pay::alipay()->success();
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
```

转账查询

```
$result = Yii::$app->pay->alipay->find([
    'out_trade_no' => '转账单号',
]);
```

单笔转账文档：https://opendocs.alipay.com/apis/api_28/alipay.fund.trans.toaccount.transfer

### 微信

订单

```
// 生成订单
$order = [
    'body' => 'test', // 内容
    'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999), // 订单号
    'total_fee' => 1,
    'notify_url' => 'http://rageframe.com/notify.php', // 回调地址
    // 'open_id' => 'okFAZ0-',  //JS支付必填
    // 'auth_code' => 'ojPztwJ5bRWRt_Ipg',  刷卡支付必填
];
```

生成参数

```
// 公众号支付
$resConfig = Yii::$app->pay->wechat->mp($order);

// 小程序支付
$resConfig = Yii::$app->pay->wechat->mini($order);

// 原生扫码支付(二维码)
$resConfig = Yii::$app->pay->wechat->sacn($order);

// app支付
$resConfig = Yii::$app->pay->wechat->app($order);

// 刷卡支付
$resConfig = Yii::$app->pay->wechat->pos($order);

// H5支付(非微信内)
$resConfig = Yii::$app->pay->wechat->map($order);
```

回调

```
$result = Yii::$app->pay->wechat->callback();

return Pay::wechat()->success();
```

关闭订单

```
$response = Yii::$app->pay->wechat->close($out_trade_no);
```

查询订单

```
$response = Yii::$app->pay->wechat->query($transaction_id);
```

退款

```
$info = [
    'transaction_id' => $transaction_id, //The wechat trade no
    'out_refund_no'  => $outRefundNo,
    'total_fee'      => 1, //=0.01
    'refund_fee'     => 1, //=0.01
];

$response = Yii::$app->pay->wechat->refund($info);
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
    'orderDesc' => 'My order title', //Order Title
    'txnAmt'    => '100', //Order Total Fee
];
```

生成参数

```
// app支付
$resConfig = Yii::$app->pay->union($config)->app($order);

// pc/wap
$resConfig = Yii::$app->pay->union($config)->html($order);
```

回调

```
$response = Yii::$app->pay->union->notify();

if ($response->isPaid()) {
    //pay success
} else {
    //pay fail
}
```

查询订单

```
$response  = Yii::$app->pay->union->query($orderId, $txnTime, $txnAmt);

// 获取 $queryId
$queryId = $response['queryId'];
```

关闭订单

```
$response  = Yii::$app->pay->union->query($orderId, $txnTime, $txnAmt, $queryId);
```

退款

```
$response  = Yii::$app->pay->union->refund($orderId, $txnTime, $txnAmt, $queryId);
```
