<?php

namespace common\models\member;

use common\enums\StatusEnum;

/**
 * This is the model class for table "rf_member_invoice".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property int|null $member_id 用户id
 * @property string|null $title 公司抬头
 * @property string|null $duty_paragraph 公司税号
 * @property string|null $opening_bank 公司开户行
 * @property string|null $opening_bank_account 公司开户行账号
 * @property string|null $address 公司地址
 * @property string|null $phone 公司电话
 * @property string|null $remark 备注
 * @property int|null $is_default 默认
 * @property int|null $type 类型 1企业 2个人
 * @property int|null $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Invoice extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_member_invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'type'], 'required'],
            [['merchant_id', 'store_id', 'member_id', 'is_default', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'duty_paragraph', 'opening_bank'], 'string', 'max' => 200],
            [['opening_bank_account'], 'string', 'max' => 100],
            [['address', 'remark'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 50],
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
            'member_id' => '用户id',
            'title' => '抬头', // 公司抬头
            'duty_paragraph' => '公司税号', // 公司税号
            'opening_bank' => '公司开户行',
            'opening_bank_account' => '公司开户行账号',
            'address' => '公司地址',
            'phone' => '公司电话',
            'remark' => '备注',
            'is_default' => '默认',
            'type' => '类型', // 1:企业; 2:个人
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (($this->isNewRecord || $this->oldAttributes['is_default'] == StatusEnum::DISABLED) && $this->is_default == StatusEnum::ENABLED) {
            self::updateAll(['is_default' => StatusEnum::DISABLED], ['member_id' => $this->member_id, 'is_default' => StatusEnum::ENABLED]);
        }

        return parent::beforeSave($insert);
    }
}
