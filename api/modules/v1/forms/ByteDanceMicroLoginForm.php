<?php

namespace api\modules\v1\forms;

use Yii;
use yii\base\Model;

/**
 * Class ByteDanceMicroLoginForm
 * @package api\modules\v1\forms
 * @author jianyan74 <751393839@qq.com>
 */
class ByteDanceMicroLoginForm extends Model
{
    public $iv;
    public $rawData;
    public $encryptedData;
    public $signature;
    public $code;

    public $auth;

    /**
     * @var string
     */
    protected $openid;
    /**
     * 匿名用户在当前小程序的 ID，如果请求时有 anonymous_code 参数才会返回
     *
     * @var string
     */
    protected $anonymous_openid;
    /**
     * @var
     */
    protected $unionid;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iv', 'rawData', 'encryptedData', 'signature', 'code'], 'required'],
            [['signature'], 'authVerify'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'iv' => '加密算法的初始向量',
            'rawData' => '不包括敏感信息的原始数据字符串，用于计算签名',
            'encryptedData' => '包括敏感数据在内的完整用户信息的加密数据',
            'signature' => '签名',
            'code' => 'code码',
            'auth' => '授权秘钥',
        ];
    }

    /**
     * @param $attribute
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function authVerify($attribute)
    {
        $auth = Yii::$app->byteDance->miniProgram->auth->session($this->code);
        // 解析是否接口报错
        Yii::$app->services->base->getWechatError($auth);

        // $sign = hash_hmac("sha256", $this->rawData, $auth['session_key']);
        // if ($sign !== $this->signature) {
        //     $this->addError($attribute, '签名错误');
        //     return;
        // }

        $this->auth = $auth;
        $this->openid = $auth['openid'] ?? '';
        $this->anonymous_openid = $auth['anonymous_openid'] ?? '';
        $this->unionid = $auth['unionid'] ?? '';
    }

    /**
     * @return mixed
     */
    public function getOpenid()
    {
        return $this->openid;
    }

    /**
     * @return mixed
     */
    public function getAnonymousOpenid()
    {
        return $this->anonymous_openid;
    }

    /**
     * @return mixed
     */
    public function getUnionId()
    {
        return $this->unionid;
    }

    /**
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\DecryptException
     */
    public function getUser()
    {
        $user = Yii::$app->wechat->miniProgram->encryptor->decryptData(
            $this->auth['session_key'],
            $this->iv,
            $this->encryptedData
        );

        !empty($this->openid) && $user['openid'] = $this->openid;
        !empty($this->unionid) && $user['unionid'] = $this->unionid;
        !empty($this->anonymous_openid) && $user['anonymous_openid'] = $this->anonymous_openid;

        return $user;
    }
}
