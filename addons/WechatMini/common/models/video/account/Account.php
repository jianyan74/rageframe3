<?php

namespace addons\WechatMini\common\models\video\account;

use Yii;

/**
 * This is the model class for table "{{%addon_wechat_mini_video_account}}".
 *
 * @property int $id
 * @property int|null $merchant_id
 * @property int|null $store_id 门店ID
 * @property string|null $service_agent_path 客服地址
 * @property string|null $service_agent_phone 客服联系方式
 * @property string|null $service_agent_type 客服类型 支持多个，0: 小程序官方客服，1: 自定义客服path 2: 联系电话
 * @property string|null $default_receiving_address 默认退货地址
 * @property int|null $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Account extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_mini_video_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['service_agent_type', 'default_receiving_address'], 'safe'],
            [['service_agent_path', 'service_agent_phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => 'Merchant ID',
            'store_id' => '门店ID',
            'service_agent_path' => '客服地址',
            'service_agent_phone' => '客服联系方式',
            'service_agent_type' => '客服类型',
            'default_receiving_address' => '默认退货地址',
            'status' => '状态(-1:已删除,0:禁用,1:正常)',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
