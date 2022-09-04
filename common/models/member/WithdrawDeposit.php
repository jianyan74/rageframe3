<?php

namespace common\models\member;

use common\traits\HasOneMember;
use common\traits\HasOneMerchant;

/**
 * This is the model class for table "{{%member_withdraw_deposit}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $member_id 会员id
 * @property int|null $member_type 1:会员;2:后台管理员;3:商家管理员
 * @property string|null $withdraw_no 提现流水号
 * @property string|null $batch_no 批量转账单号
 * @property string $realname 真实姓名
 * @property string $mobile 手机号
 * @property string|null $account_number 银行账号
 * @property int|null $account_type 账户类型
 * @property string|null $account_type_name 账户类型名称
 * @property string|null $bank_name 银行信息
 * @property string|null $bank_branch 银行支行信息
 * @property string|null $identity_card 身份证
 * @property string|null $identity_card_front 身份证正面
 * @property string|null $identity_card_back 身份证背面
 * @property float|null $cash 提现金额
 * @property string|null $memo 备注
 * @property int|null $transfer_type 转账方式
 * @property string|null $transfer_name 转账银行名称
 * @property float|null $transfer_money 转账金额
 * @property string|null $transfer_remark 转账备注
 * @property string|null $transfer_no 转账流水号
 * @property string|null $transfer_account_no 转账银行账号
 * @property string|null $transfer_result 转账结果
 * @property int|null $transfer_status 转账状态
 * @property int|null $transfer_time 转账申请时间
 * @property int|null $payment_time 到账时间
 * @property int|null $audit_time 审核时间
 * @property float|null $service_charge 手续费率金额
 * @property float|null $service_charge_rate 手续费率
 * @property float|null $service_charge_single 手续费单笔
 * @property float|null $service_charge_total 总手续费
 * @property string|null $refusal_cause 拒绝原因
 * @property string|null $addon_name 插件名称
 * @property int|null $is_addon 是否插件
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class WithdrawDeposit extends \common\models\base\BaseModel
{
    use HasOneMember, HasOneMerchant;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_withdraw_deposit}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'member_id', 'member_type', 'account_type', 'transfer_type', 'transfer_status', 'transfer_time', 'payment_time', 'audit_time', 'is_addon', 'status', 'created_at', 'updated_at'], 'integer'],
            [['cash', 'transfer_money', 'service_charge', 'service_charge_rate', 'service_charge_single', 'service_charge_total'], 'number', 'min' => 0],
            [['withdraw_no', 'batch_no', 'bank_name', 'transfer_no', 'transfer_account_no', 'addon_name'], 'string', 'max' => 100],
            [['realname', 'account_number', 'transfer_name'], 'string', 'max' => 50],
            [['mobile', 'identity_card'], 'string', 'max' => 20],
            [['account_type_name'], 'string', 'max' => 30],
            [['bank_branch', 'identity_card_front', 'identity_card_back', 'memo', 'transfer_remark', 'transfer_result', 'refusal_cause'], 'string', 'max' => 200],
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
            'member_type' => '1:会员;2:后台管理员;3:商家管理员',
            'batch_no' => '批量转账单号',
            'withdraw_no' => '提现流水号',
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
            'cash' => '提现金额',
            'memo' => '备注',
            'transfer_type' => '转账方式',
            'transfer_name' => '转账银行名称',
            'transfer_money' => '转账金额',
            'transfer_remark' => '转账备注',
            'transfer_no' => '转账流水号',
            'transfer_account_no' => '转账银行账号',
            'transfer_result' => '转账结果',
            'transfer_status' => '转账状态',
            'transfer_time' => '转账提交时间',
            'payment_time' => '到账时间',
            'audit_time' => '审核时间',
            'service_charge' => '手续费率金额',
            'service_charge_rate' => '手续费率',
            'service_charge_single' => '手续费单笔',
            'service_charge_total' => '总手续费',
            'addon_name' => '插件名称',
            'is_addon' => '是否插件',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'refusal_cause' => '拒绝原因',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
