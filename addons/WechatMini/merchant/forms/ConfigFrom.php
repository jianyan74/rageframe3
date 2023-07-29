<?php

namespace addons\WechatMini\merchant\forms;

use yii\base\Model;

/**
 * Class ConfigFrom
 * @package addons\WechatMini\merchant\forms
 * @author jianyan74 <751393839@qq.com>
 */
class ConfigFrom extends Model
{
    public $wechat_mini_app_id;
    public $wechat_mini_secret;
    public $wechat_mini_token;
    public $wechat_mini_encodingaeskey;

    /**
     * 无需加入 rule
     *
     * @var
     */
    public $wechat_mini_url;

    public function rules()
    {
        return [
            [['wechat_mini_app_id', 'wechat_mini_secret', 'wechat_mini_token', 'wechat_mini_encodingaeskey'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'wechat_mini_app_id' => 'App ID',
            'wechat_mini_secret' => 'App Secret',
            'wechat_mini_token' => 'Token(令牌)',
            'wechat_mini_encodingaeskey' => 'EncodingAESKey',
            'wechat_mini_url' => 'URL(服务器地址)',
        ];
    }
}
