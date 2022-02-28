<?php

namespace common\models\extend;

use Yii;
use common\traits\HasOneMember;

/**
 * This is the model class for table "{{%extend_pay_log}}".
 *
 * @property int $id 主键
 * @property int|null $merchant_id 商户id
 * @property int|null $member_id 用户id
 * @property string|null $app_id 应用id
 * @property string|null $out_trade_no 商户订单号
 * @property string|null $order_sn 关联订单号
 * @property string|null $order_group 组别[默认统一支付类型]
 * @property string|null $openid openid
 * @property string|null $mch_id 商户支付账户
 * @property string|null $body 支付内容
 * @property string|null $detail 支付详情
 * @property string|null $auth_code 刷卡码
 * @property string|null $transaction_id 关联订单号
 * @property float|null $total_fee 初始金额
 * @property string|null $fee_type 标价币种
 * @property int $pay_type 支付类型
 * @property float $pay_fee 支付金额
 * @property int|null $pay_status 支付状态
 * @property int|null $pay_time 支付时间
 * @property string|null $trade_type 交易类型
 * @property float $refund_fee 退款金额
 * @property int|null $refund_type 退款情况[0:未退款;1部分退款;2:全部退款]
 * @property string|null $create_ip 创建者ip
 * @property string|null $pay_ip 支付者ip
 * @property string|null $unite_no 联合订单号
 * @property string|null $notify_url 支付通知回调地址
 * @property string|null $return_url 买家付款成功跳转地址
 * @property int|null $is_addon 是否插件
 * @property string|null $addon_name 插件名称
 * @property int|null $is_error 是否有报错
 * @property string|null $error_data 报错日志
 * @property string|null $req_id 唯一ID
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class PayLog extends \common\models\base\BaseModel
{
    use HasOneMember;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%extend_pay_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'member_id', 'pay_type', 'pay_status', 'pay_time', 'refund_type', 'is_addon', 'is_error', 'status', 'created_at', 'updated_at'], 'integer'],
            [['total_fee', 'pay_fee', 'refund_fee'], 'number'],
            [['error_data'], 'safe'],
            [['app_id', 'openid', 'auth_code', 'transaction_id', 'req_id'], 'string', 'max' => 50],
            [['out_trade_no'], 'string', 'max' => 32],
            [['order_sn', 'create_ip', 'pay_ip', 'unite_no'], 'string', 'max' => 30],
            [['order_group', 'mch_id', 'fee_type'], 'string', 'max' => 20],
            [['body', 'detail', 'notify_url', 'return_url'], 'string', 'max' => 100],
            [['trade_type'], 'string', 'max' => 16],
            [['addon_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'merchant_id' => '商户id',
            'member_id' => '用户id',
            'app_id' => '应用id',
            'out_trade_no' => '商户订单号',
            'order_sn' => '关联订单号',
            'order_group' => '组别[默认统一支付类型]',
            'openid' => 'openid',
            'mch_id' => '商户支付账户',
            'body' => '支付内容',
            'detail' => '支付详情',
            'auth_code' => '刷卡码',
            'transaction_id' => '关联订单号',
            'total_fee' => '初始金额',
            'fee_type' => '标价币种',
            'pay_type' => '支付类型',
            'pay_fee' => '支付金额',
            'pay_status' => '支付状态',
            'pay_time' => '支付时间',
            'trade_type' => '交易类型',
            'refund_fee' => '退款金额',
            'refund_type' => '退款情况[0:未退款;1部分退款;2:全部退款]',
            'create_ip' => '创建者ip',
            'pay_ip' => '支付者ip',
            'unite_no' => '联合订单号',
            'notify_url' => '支付通知回调地址',
            'return_url' => '买家付款成功跳转地址',
            'is_addon' => '是否插件',
            'addon_name' => '插件名称',
            'is_error' => '是否有报错',
            'error_data' => '报错日志',
            'req_id' => '唯一ID',
            'status' => '状态[-1:删除;0:禁用;1启用]',
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
        if ($this->isNewRecord) {
            $this->app_id = Yii::$app->id;
            $this->create_ip = Yii::$app->services->base->getUserIp();
            $this->addon_name = Yii::$app->params['addon']['name'] ?? '';
            $this->req_id = Yii::$app->params['uuid'];
            !empty($this->addon_name) && $this->is_addon = 1;
        }

        return parent::beforeSave($insert);
    }
}
