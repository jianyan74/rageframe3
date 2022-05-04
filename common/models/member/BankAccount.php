<?php

namespace common\models\member;

use common\behaviors\MerchantBehavior;
use common\enums\AccountTypeEnum;
use common\enums\StatusEnum;

/**
 * This is the model class for table "{{%member_bank_account}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $member_id 会员id
 * @property int|null $member_type 会员类型
 * @property string $realname 真实姓名
 * @property string $mobile 手机号
 * @property string|null $account_number 银行账号
 * @property int|null $account_type 账户类型
 * @property string|null $account_type_name 账户类型名称
 * @property string|null $bank_name 银行信息
 * @property string|null $bank_branch 银行支行信息
 * @property string|null $bank_number 银行编码
 * @property string|null $identity_card 身份证
 * @property string|null $identity_card_front 身份证正面
 * @property string|null $identity_card_back 身份证背面
 * @property int|null $is_default 是否默认账号
 * @property int|null $audit_status 审核状态
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class BankAccount extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_bank_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_type', 'realname', 'mobile'], 'required'],
            ['account_type', 'in', 'range' => AccountTypeEnum::getKeys()],
            [['merchant_id', 'member_id', 'member_type', 'account_type', 'is_default', 'audit_status', 'status', 'created_at', 'updated_at'], 'integer'],
            [['realname', 'account_number'], 'string', 'max' => 50],
            [['mobile', 'identity_card'], 'string', 'max' => 20],
            [['account_type_name'], 'string', 'max' => 30],
            [['bank_name'], 'string', 'max' => 100],
            [['bank_branch', 'identity_card_front', 'identity_card_back'], 'string', 'max' => 200],
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
            'member_id' => '会员id',
            'member_type' => '会员类型',
            'realname' => '真实姓名',
            'mobile' => '手机号',
            'account_number' => '银行账号',
            'account_type' => '账户类型',
            'account_type_name' => '账户类型名称',
            'bank_name' => '银行信息',
            'bank_branch' => '银行支行信息',
            'identity_card' => '身份证',
            'identity_card_front' => '身份证正面',
            'identity_card_back' => '身份证背面',
            'is_default' => '默认账号',
            'audit_status' => '审核状态',
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
        $this->account_type_name = AccountTypeEnum::getValue($this->account_type);
        if (($this->isNewRecord || $this->oldAttributes['is_default'] == StatusEnum::DISABLED) && $this->is_default == StatusEnum::ENABLED) {
            self::updateAll(['is_default' => StatusEnum::DISABLED], ['member_id' => $this->member_id, 'merchant_id' => $this->merchant_id, 'is_default' => StatusEnum::ENABLED]);
        }

        if ($this->account_type != AccountTypeEnum::UNION) {
            $this->bank_name = '';
            $this->bank_branch = '';
        }

        return parent::beforeSave($insert);
    }
}
