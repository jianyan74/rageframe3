## 接口加密

目录

- 启动加密
- 基本说明
- Get请求
- Post请求
- Put/Delete请求

> 注意默认是关闭加密，可自行开启

#### 启动加密

具体查看文件 `api/config/params.php`

```
// 签名验证默认关闭验证，如果开启需了解签名生成及验证
'user.httpSignValidity' => false,
// 签名授权公钥秘钥
'user.httpSignAccount' => [
    'doormen' => 'e3de3825cfbf',
],
```

#### 基本说明

Url测试访问地址:

```
// 注意系统默认关闭了该测试控制器 请去 api 的 main 文件内开启 sign-secret-key 路由
http://www.example.com/api/sign-secret-key/index
```

签名sign的生成规则：  
将需要参与签名的参数按照参数名字符串顺序升序排列，并用请求查询串的形式依次拼接。  
格式为：p1=v1&p2=v2&p3=v3  
将以上拼好的结果后面直接加上appSecret,形成待签名字符串  
对待签名字符串按照UTF-8编码做MD5摘要运算，结果转化为32位小写签名摘要。

固定参数

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
appId | string | 是 | 无 | 授权公钥 | 后台给予，文档测试值:doormen
nonceStr | string | 是 | 无 | 随机数 | 自行生成 默认8位，文档测试值:z7cl7WR9
time | int | 是 | 无 | 时间戳 | 自行生成 10位，注意和当前校验时间不能大于60秒，文档测试值:1539846942
sign | string | 是 | 无 | 签名 | 加密后出现

不用传参但是需要参与加密

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
appSecret | string | 是 | 无 | 授权秘钥 | 后台给与，文档测试值:e3de3825cfbf

#### Get 请求

1、例如以下的接口请求方式

请求地址(Get)

```
/v1/site/send
```

参数

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
mobile | string| 是 | 无 | 账号 | 测试值为：15888888888

##### 请求时候测试拼接字符串为：

Get请求所有的参数都是在url里面所以mobile加入计算

```
appId=doormen&mobile=15888888888&nonceStr=z7cl7WR9&time=1539846942e3de3825cfbf

// php版加密方式
$sign = strtolower(md5('上面的字符串'));

// 测试生成的sign：
94c897114201d7f9b4adf03b5e3afc8f

// 查看最后生成的Url：
appId=doormen&mobile=15888888888&nonceStr=z7cl7WR9&time=1539846942&sign=94c897114201d7f9b4adf03b5e3afc8f
```

#### Post请求

1、例如以下的接口请求方式

请求地址(Post)

```
/v1/site/login
```

参数

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
username | string| 是 | 无 | 账号 |
password | string| 是 | 无 | 密码 | 

2、上面的参数无需加密，只需要加密基本的参数

```
// 默认参数
appId=doormen&nonceStr=z7cl7WR9&time=1539846942e3de3825cfbf

// php版加密方式
$sign = strtolower(md5('上面的字符串'));

// 测试生成的sign：
94c897114201d7f9b4adf03b5e3afc8f

// 查看最后生成的Url：
/v1/site/login?appId=doormen&nonceStr=z7cl7WR9&time=1539846942&sign=94c897114201d7f9b4adf03b5e3afc8f
```

#### Put/Delete请求

1、例如以下的接口请求方式

请求地址(Put/Delete)

```
/v1/member/member/view?id=[ID]
```

参数

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
nickname | string | 是 | 无 | 昵称 | 

2、上面的参数无需加密，只需要加密 url 基本的参数

```
// 默认参数
appId=doormen&id=1&nonceStr=z7cl7WR9&time=1539846942e3de3825cfbf

// php版加密方式
$sign = strtolower(md5('上面的字符串'));

// 测试生成的sign：
94c897114201d7f9b4adf03b5e3afc8f

// 查看最后生成的Url：
/v1/member/member/view?id=1&appId=doormen&nonceStr=z7cl7WR9&time=1539846942&sign=94c897114201d7f9b4adf03b5e3afc8f
```
