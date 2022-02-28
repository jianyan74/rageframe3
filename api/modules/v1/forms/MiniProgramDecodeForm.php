<?php

namespace api\modules\v1\forms;

use Yii;
use yii\base\Model;

/**
 * Class MiniProgramLoginForm
 * @package api\modules\v1\models
 */
class MiniProgramDecodeForm extends Model
{
    public $iv;
    public $encryptedData;
    public $code;
    public $auth;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iv', 'encryptedData', 'code'], 'required'],
            [['code'], 'authVerify'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'iv' => '加密算法的初始向量',
            'encryptedData' => '包括敏感数据在内的完整用户信息的加密数据',
            'code' => 'code码',
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
        $auth = Yii::$app->wechat->miniProgram->auth->session($this->code);
        // 解析是否接口报错
        Yii::$app->services->base->getWechatError($auth);

        $this->auth = $auth;
    }

    /**
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\DecryptException
     */
    public function getUser()
    {
        return Yii::$app->wechat->miniProgram->encryptor->decryptData($this->auth['session_key'], $this->iv, $this->encryptedData);
    }
}
