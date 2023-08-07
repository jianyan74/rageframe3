<?php

namespace addons\Wechat\merchant\forms;

use yii\base\Model;

/**
 * Class ConfigFrom
 * @package addons\Wechat\merchant\forms
 * @author jianyan74 <751393839@qq.com>
 */
class ConfigFrom extends Model
{
    public $wechat_mp_account;
    public $wechat_mp_id;
    public $wechat_mp_qrcode;

    public $wechat_mp_app_id;
    public $wechat_mp_appsecret;
    public $wechat_mp_token;
    public $wechat_mp_encodingaeskey;

    /**
     * 无需加入 rule
     *
     * @var
     */
    public $wechat_mp_url;

    public function rules()
    {
        return [
            [['wechat_mp_app_id', 'wechat_mp_appsecret', 'wechat_mp_token', 'wechat_mp_encodingaeskey'], 'string'],
            [['wechat_mp_account', 'wechat_mp_id', 'wechat_mp_qrcode'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'wechat_mp_id' => '原始ID',
            'wechat_mp_account' => '公众号账号',
            'wechat_mp_qrcode' => '公众号二维码',
            'wechat_mp_app_id' => 'App ID',
            'wechat_mp_appsecret' => 'App Secret',
            'wechat_mp_token' => 'Token(令牌)',
            'wechat_mp_encodingaeskey' => 'EncodingAESKey',
            'wechat_mp_url' => 'URL(服务器地址)',
        ];
    }
}
