<?php

namespace addons\WechatMini\common\models\video\spu;

use addons\WechatMini\common\models\video\common\Cate;

/**
 * This is the model class for table "{{%addon_wechat_mini_video_spu}}".
 *
 * @property int|null $product_id 交易组件平台内部商品ID
 * @property int $out_product_id 商家自定义商品ID
 * @property int|null $merchant_id 商户ID
 * @property int|null $store_id 门店ID
 * @property string|null $title 标题
 * @property string|null $path 路径
 * @property string|null $head_img 主图,多张,列表
 * @property string|null $desc_info 商品详情图文
 * @property string|null $audit_info 修改时间记录
 * @property string|null $reject_reason 审核原因
 * @property int $third_cat_id 品类
 * @property int $brand_id 品牌
 * @property string|null $qualification_pics 商品资质图片
 * @property string|null $info_version 预留字段，用于版本控制
 * @property string|null $scene_group_list 商品使用场景
 * @property string|null $delist_info
 * @property string|null $spu_attrs
 * @property string|null $sale_time
 * @property string|null $labels
 * @property string|null $promoter_wechat_numbers
 * @property string|null $cats
 * @property int|null $status 状态
 * @property int|null $edit_status 审核状态
 * @property int|null $create_time 创建时间
 * @property int|null $update_time 修改时间
 */
class Spu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_mini_video_spu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'out_product_id', 'merchant_id', 'store_id', 'third_cat_id', 'brand_id', 'status', 'edit_status', 'create_time', 'update_time'], 'integer'],
            [['out_product_id'], 'required'],
            [['head_img', 'desc_info', 'audit_info', 'qualification_pics', 'scene_group_list', 'delist_info', 'spu_attrs', 'sale_time', 'labels', 'promoter_wechat_numbers', 'cats'], 'safe'],
            [['title', 'path', 'reject_reason', 'info_version'], 'string', 'max' => 255],
            [['out_product_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => '交易组件平台内部商品ID',
            'out_product_id' => '自定义商品ID',
            'merchant_id' => '商户ID',
            'store_id' => '门店ID',
            'title' => '标题',
            'path' => '路径',
            'head_img' => '主图,多张,列表',
            'desc_info' => '商品详情图文',
            'audit_info' => '修改时间记录',
            'reject_reason' => '审核原因',
            'third_cat_id' => '品类',
            'brand_id' => '品牌',
            'qualification_pics' => '商品资质图片',
            'info_version' => '预留字段，用于版本控制',
            'scene_group_list' => '商品使用场景',
            'delist_info' => 'Delist Info',
            'spu_attrs' => 'Spu Attrs',
            'sale_time' => 'Sale Time',
            'labels' => 'Labels',
            'promoter_wechat_numbers' => 'Promoter Wechat Numbers',
            'cats' => 'Cats',
            'status' => '状态',
            'edit_status' => '审核状态',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSkus()
    {
        return $this->hasMany(Sku::class, ['out_product_id' => 'out_product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(Cate::class, ['id' => 'third_cat_id']);
    }
}
