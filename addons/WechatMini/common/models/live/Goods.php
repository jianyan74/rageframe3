<?php

namespace addons\WechatMini\common\models\live;

use Yii;

/**
 * This is the model class for table "{{%addon_wechat_mini_live_goods}}".
 *
 * @property int $id id
 * @property int|null $merchant_id 商户id
 * @property string|null $name 商品名称
 * @property string|null $cover_img 商品封面图链接
 * @property string|null $cover_media 商品封面资源ID
 * @property string|null $url 商品小程序路径
 * @property float|null $price 商品价格(分)
 * @property float|null $price_two 商品价格，使用方式看price_type
 * @property int|null $price_type 价格类型，1：一口价（只需要传入price，price2不传） 2：价格区间（price字段为左边界，price2字段为右边界，price和price2必传） 3：显示折扣价（price字段为原价，price2字段为现价， price和price2必传）
 * @property int|null $goods_id 商品id
 * @property string|null $explain_url 商品讲解视频
 * @property string|null $third_party_appid 第三方商品appid ,当前小程序商品则为空
 * @property int|null $third_party_tag 1、2：表示是为 API 添加商品，否则是直播控制台添加的商品
 * @property int|null $audit_status 0：未审核，1：审核中，2:审核通过，3审核失败
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_mini_live_goods}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'price_type', 'goods_id', 'third_party_tag', 'audit_status', 'status', 'created_at', 'updated_at'], 'integer'],
            [['price', 'price_two'], 'number'],
            [['name', 'cover_img', 'url', 'explain_url'], 'string', 'max' => 255],
            [['cover_media'], 'string', 'max' => 200],
            [['third_party_appid'], 'string', 'max' => 50],
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
            'name' => '商品名称',
            'cover_img' => '商品封面图链接',
            'cover_media' => '商品封面资源ID',
            'url' => '商品小程序路径',
            'price' => '商品价格(分)',
            'price_two' => '商品价格，使用方式看price_type',
            'price_type' => '价格类型，1：一口价（只需要传入price，price2不传） 2：价格区间（price字段为左边界，price2字段为右边界，price和price2必传） 3：显示折扣价（price字段为原价，price2字段为现价， price和price2必传）',
            'goods_id' => '商品id',
            'explain_url' => '商品讲解视频',
            'third_party_appid' => '第三方商品appid ,当前小程序商品则为空',
            'third_party_tag' => '1、2：表示是为 API 添加商品，否则是直播控制台添加的商品',
            'audit_status' => '0：未审核，1：审核中，2:审核通过，3审核失败',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
