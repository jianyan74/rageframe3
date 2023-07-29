<?php

namespace addons\WechatMini\common\models\video\order;

use Yii;

/**
 * This is the model class for table "{{%addon_wechat_capabilities_order_aftersale}}".
 *
 * @property int $out_order_id 订单ID
 * @property int $out_aftersale_id 售后ID
 * @property int $merchant_id 商户ID
 * @property string $path 路径
 * @property int $type 退款类型 1:退款,2:退款退货,3:换货
 * @property int $finish_all_aftersale 0:订单可继续售后, 1:订单无继续售后
 * @property string $refund 退款金额
 * @property array $product_infos 退货相关商品列表
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class AfterSale extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_mini_video_order_aftersale}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['out_order_id'], 'required'],
            [['out_order_id', 'out_aftersale_id', 'merchant_id', 'type', 'finish_all_aftersale', 'status', 'created_at', 'updated_at'], 'integer'],
            [['refund'], 'number'],
            [['product_infos'], 'safe'],
            [['path'], 'string', 'max' => 255],
            [['out_order_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'out_order_id' => '订单ID',
            'out_aftersale_id' => '售后ID',
            'merchant_id' => '商户ID',
            'path' => '路径',
            'type' => '退款类型 1:退款,2:退款退货,3:换货',
            'finish_all_aftersale' => '0:订单可继续售后, 1:订单无继续售后',
            'refund' => '退款金额',
            'product_infos' => '退货相关商品列表',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
