<?php

namespace addons\WechatMini\common\models\video\spu;

/**
 * This is the model class for table "{{%addon_wechat_mini_video_spu_sku}}".
 *
 * @property int|null $merchant_id
 * @property int|null $store_id 门店ID
 * @property int|null $sku_id
 * @property int $out_sku_id 商家自定义skuID
 * @property int $out_product_id 商家自定义商品ID
 * @property string $thumb_img sku小图
 * @property float $sale_price 售卖价格
 * @property float $market_price 市场价格
 * @property int $stock_num 库存
 * @property string|null $barcode 条形码
 * @property string|null $sku_code 商品编码
 * @property string|null $sku_attrs
 */
class Sku extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_mini_video_spu_sku}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'sku_id', 'out_sku_id', 'out_product_id', 'stock_num'], 'integer'],
            [['out_sku_id', 'thumb_img'], 'required'],
            [['sale_price', 'market_price'], 'number'],
            [['sku_attrs'], 'safe'],
            [['thumb_img'], 'string', 'max' => 255],
            [['barcode', 'sku_code'], 'string', 'max' => 100],
            [['out_sku_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'merchant_id' => 'Merchant ID',
            'store_id' => '门店ID',
            'sku_id' => 'Sku ID',
            'out_sku_id' => '商家自定义skuID',
            'out_product_id' => '商家自定义商品ID',
            'thumb_img' => 'sku小图',
            'sale_price' => '售卖价格',
            'market_price' => '市场价格',
            'stock_num' => '库存',
            'barcode' => '条形码',
            'sku_code' => '商品编码',
            'sku_attrs' => 'Sku Attrs',
        ];
    }
}
