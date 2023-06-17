<?php

namespace addons\Wechat\common\models;

use common\models\member\Auth;

/**
 * This is the model class for table "rf_addon_wechat_message_history".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property int|null $rule_id 规则id
 * @property int|null $keyword_id 关键字id
 * @property string|null $openid
 * @property string|null $module 触发模块
 * @property int|null $is_addon 是否插件
 * @property string $addon_name 插件名称
 * @property string|null $message 微信消息
 * @property string|null $type
 * @property string|null $event 详细事件
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class MessageHistory extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_message_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'rule_id', 'keyword_id', 'is_addon', 'status', 'created_at', 'updated_at'], 'integer'],
            [['openid', 'module'], 'string', 'max' => 50],
            [['addon_name'], 'string', 'max' => 100],
            [['message'], 'string', 'max' => 1000],
            [['type', 'event'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户id',
            'store_id' => '店铺ID',
            'rule_id' => '规则id',
            'keyword_id' => '关键字id',
            'openid' => 'openId',
            'module' => '触发模块',
            'is_addon' => '是否插件',
            'addon_name' => '插件名称',
            'message' => '微信消息',
            'type' => 'Type',
            'event' => '详细事件',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 关联粉丝
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFans()
    {
        return $this->hasOne(Fans::class, ['openid' => 'openid']);
    }

    /**
     * 关联授权
     */
    public function getAuth()
    {
        return $this->hasOne(Auth::class, ['oauth_client_user_id' => 'openid']);
    }

    /**
     * 关联规则
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRule()
    {
        return $this->hasOne(Rule::class, ['id' => 'rule_id']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            !empty($this->addon_name) && $this->is_addon = 1;
        }

        return parent::beforeSave($insert);
    }
}
