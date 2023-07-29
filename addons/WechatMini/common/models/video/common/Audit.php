<?php

namespace addons\WechatMini\common\models\video\common;

/**
 * This is the model class for table "{{%addon_wechat_capabilities_account_audit}}".
 *
 * @property int $id
 * @property int $merchant_id 商户ID
 * @property array $data 审核内容
 * @property string $audit_id 审核关联ID
 * @property int $audit_type 1:品牌;2:类目
 * @property int $audit_time 审核时间
 * @property int $map_id 关联ID
 * @property string $reject_reason 拒绝原因
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Audit extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_mini_video_audit}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'audit_type', 'map_id', 'audit_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['data'], 'safe'],
            [['audit_id', 'reject_reason'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户ID',
            'data' => '审核内容',
            'audit_id' => '审核关联ID',
            'audit_type' => '1:品牌;2:类目',
            'map_id' => '关联ID',
            'reject_reason' => '拒绝原因',
            'status' => '状态',
            'audit_time' => '审核时间',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
