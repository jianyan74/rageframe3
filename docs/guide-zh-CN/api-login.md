group (组别) | 说明
---|---
default | 默认
pc | PC
iOS | 苹果
android | 安卓
app | app
h5 | H5
wechatMp | 微信公众号
wechatMini | 微信小程序
aliMini | 支付宝小程序
qqMini | QQ小程序
baiduMini | 百度小程序
bytedanceMini | 字节跳动小程序
dingTalkMini | 钉钉小程序

> 注意：如果是商户 api 的需要安装商户插件，并创建用户信息进行登录

## 登录重置

目录

- 登录
- 退出登录
- 重置令牌
- 发送短信
- 手机号登录
- 注册

### 登录

请求地址(Post)

```
/v1/site/login
```

入参说明

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
username | string| 是 | 无 | 账号 |
password | string| 是 | 无 | 密码 | 
group | string| 是 | 无 | 组别 |

出参说明

参数名 | 参数类型 | 说明 | 备注
-------|----------|------|-----
refresh_token | string | 刷新token
access_token | string | 登录token
expiration_time | string | 过期时间
member | array | 用户个人信息
-- id | int | 用户ID
-- nickname | string | 用户昵称
-- account | array | 用户账号信息
-- -- user_money | double | 当前余额
-- -- user_integral | double | 当前积分
-- memberLevel | array | 用户当前等级

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        
    }
}
```

### 重置令牌

请求地址(Post)

```
/v1/site/refresh
```

入参说明

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
refresh_token | string| 是 | 无 | 重置令牌 |
group | string| 是 | 无 | 组别 |

出参说明

> 同登录接口

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
       
    }
}
```

### 发送短信

请求地址(Post)

```
/v1/site/sms-code
```

入参说明

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
mobile | string| 是 | 无 | 手机号码 |
usage | string| 是 | 无 | 用户 | login/register/...

出参说明

> 无

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
       
    }
}
```

### 发送短信

请求地址(Post)

```
/v1/site/sms-code
```

入参说明

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
mobile | string| 是 | 无 | 手机号码 |
usage | string| 是 | 无 | 用户 | login/register/...

出参说明

> 无

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
       
    }
}
```

### 手机号登录

请求地址(Post)

```
/v1/site/mobile-login
```

入参说明

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
mobile | string| 是 | 无 | 手机号码 |
code | int| 是 | 无 | 验证码 |
group | string| 是 | 无 | 组别 |

> 同登录接口
> 
返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        
    }
}
```

### 注册

请求地址(Post)

```
/v1/site/register
```

入参说明

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
mobile | string| 是 | 无 | 手机号码 |
code | int| 是 | 无 | 验证码 |
group | string| 是 | 无 | 组别 |
password | string| 是 | 无 | 密码 |
password_repetition | string| 是 | 无 | 重复密码 |
nickname | string| 是 | 无 | 昵称 |

> 同登录接口
>
返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        
    }
}
```
