<?php

namespace common\models\extend;

use Yii;
use common\traits\HasOneMember;

/**
 * This is the model class for table "{{%extend_pay_refund}}".
 *
 * @property int $id 主键id
 * @property int|null $pay_id 支付ID
 * @property int|null $merchant_id 商户id
 * @property int|null $member_id 买家id
 * @property string|null $app_id 应用id
 * @property string|null $order_sn 关联订单号
 * @property string|null $order_group 组别[默认统一支付类型]
 * @property string|null $out_trade_no 商户订单号
 * @property string|null $transaction_id 微信订单号
 * @property string|null $refund_trade_no 退款交易号
 * @property float|null $refund_money 退款金额
 * @property int|null $refund_way 退款方式
 * @property string|null $ip 申请者ip
 * @property string|null $error_data 报错日志
 * @property int|null $is_addon 是否插件
 * @property string|null $addon_name 插件名称
 * @property string|null $remark 备注
 * @property string|null $req_id 唯一ID
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PayRefund extends \common\models\base\BaseModel
{
    use HasOneMember;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%extend_pay_refund}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pay_id', 'merchant_id', 'member_id', 'refund_way', 'is_addon', 'status', 'created_at', 'updated_at'], 'integer'],
            [['refund_money'], 'number'],
            [['error_data'], 'safe'],
            [['app_id', 'transaction_id', 'req_id'], 'string', 'max' => 50],
            [['order_sn', 'ip'], 'string', 'max' => 30],
            [['order_group'], 'string', 'max' => 20],
            [['out_trade_no'], 'string', 'max' => 32],
            [['refund_trade_no'], 'string', 'max' => 55],
            [['addon_name'], 'string', 'max' => 200],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键id',
            'pay_id' => '支付ID',
            'merchant_id' => '商户id',
            'member_id' => '买家id',
            'app_id' => '应用id',
            'order_sn' => '关联订单号',
            'order_group' => '组别[默认统一支付类型]',
            'out_trade_no' => '商户订单号',
            'transaction_id' => '微信订单号',
            'refund_trade_no' => '退款交易号',
            'refund_money' => '退款金额',
            'refund_way' => '退款方式',
            'ip' => '申请者ip',
            'error_data' => '报错日志',
            'is_addon' => '是否插件',
            'addon_name' => '插件名称',
            'remark' => '备注',
            'req_id' => '唯一ID',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
            $this->ip = Yii::$app->services->base->getUserIp();
            $this->addon_name = Yii::$app->params['addon']['name'] ?? '';
            $this->req_id = Yii::$app->params['uuid'];
            !empty($this->addon_name) && $this->is_addon = 1;
        }

        return parent::beforeSave($insert);
    }
}
