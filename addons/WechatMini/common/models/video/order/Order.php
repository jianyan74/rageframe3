<?php

namespace addons\WechatMini\common\models\video\order;

use Yii;

/**
 * This is the model class for table "{{%addon_wechat_capabilities_order}}".
 *
 * @property int $out_order_id
 * @property string $openid
 * @property int $scene 品牌ID
 * @property string $ticket 拉起收银台的ticket
 * @property int $ticket_expire_time ticket有效截止时间
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Order extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_mini_video_order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['out_order_id'], 'required'],
            [['out_order_id', 'delivery_type', 'finish_all_delivery', 'scene', 'ticket_expire_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['openid'], 'string', 'max' => 50],
            [['ticket'], 'string', 'max' => 255],
            [['out_order_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'out_order_id' => '关联订单ID',
            'openid' => 'Openid',
            'scene' => '场景指',
            'delivery_type' => '配送类型',
            'finish_all_delivery' => '发货完成状态',
            'ticket' => '拉起收银台的ticket',
            'ticket_expire_time' => 'ticket有效截止时间',
            'final_price' => '订单总金额',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(\addons\TinyShop\common\models\order\Order::class, ['id' => 'out_order_id']);
    }
}
