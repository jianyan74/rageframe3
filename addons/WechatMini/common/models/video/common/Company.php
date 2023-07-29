<?php

namespace addons\WechatMini\common\models\video\common;

/**
 * This is the model class for table "{{%addon_wechat_capabilities_account_company}}".
 *
 * @property int $merchant_id
 * @property string $delivery_id 物流ID
 * @property string $delivery_name 物流名称
 * @property int $map_id 关联系统物流ID
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Company extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_mini_video_company}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'map_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['delivery_id'], 'required'],
            [['delivery_id'], 'string', 'max' => 50],
            [['delivery_name'], 'string', 'max' => 255],
            [['delivery_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'merchant_id' => 'Merchant ID',
            'delivery_id' => '物流ID',
            'delivery_name' => '物流名称',
            'map_id' => '关联系统物流ID',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
