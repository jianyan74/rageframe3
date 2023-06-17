<?php

namespace common\models\member;

use common\behaviors\MerchantStoreBehavior;
use common\traits\HasOneMember;

/**
 * This is the model class for table "{{%member_cancel}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property int|null $member_id 会员id
 * @property string|null $content 申请内容
 * @property int $audit_status 审核状态[0:申请;1通过;-1失败]
 * @property int|null $audit_time 审核时间
 * @property string|null $refusal_cause 拒绝原因
 * @property int|null $is_addon 是否插件
 * @property string|null $addon_name 插件名称
 * @property int|null $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Cancel extends \common\models\base\BaseModel
{
    use MerchantStoreBehavior, HasOneMember;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_cancel}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'member_id', 'audit_status', 'audit_time', 'is_addon', 'status', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['refusal_cause', 'addon_name'], 'string', 'max' => 200],
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
            'member_id' => '会员id',
            'content' => '申请内容',
            'audit_status' => '审核状态', // [0:申请;1通过;-1失败]
            'audit_time' => '审核时间',
            'refusal_cause' => '拒绝原因',
            'is_addon' => '是否插件',
            'addon_name' => '插件名称',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
