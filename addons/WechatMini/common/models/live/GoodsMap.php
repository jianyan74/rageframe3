<?php

namespace addons\WechatMini\common\models\live;

use Yii;

/**
 * This is the model class for table "{{%addon_wechat_mini_live_goods_map}}".
 *
 * @property int $id id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property int|null $roomid 直播间ID
 * @property int|null $goods_id 商品id
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class GoodsMap extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_mini_live_goods_map}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'roomid', 'goods_id', 'status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'merchant_id' => '商户id',
            'store_id' => '店铺ID',
            'roomid' => '直播间ID',
            'goods_id' => '商品id',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
