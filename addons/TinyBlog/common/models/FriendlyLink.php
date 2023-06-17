<?php

namespace addons\TinyBlog\common\models;

use common\behaviors\MerchantStoreBehavior;

/**
 * This is the model class for table "{{%addon_tiny_blog_friendly_link}}".
 *
 * @property int $id 主键
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺id
 * @property string $title 标题
 * @property string|null $link 外链
 * @property int $view 浏览量
 * @property int $sort 优先级
 * @property int|null $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class FriendlyLink extends \common\models\base\BaseModel
{
    use MerchantStoreBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_tiny_blog_friendly_link}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'view', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'link'], 'required'],
            [['title'], 'string', 'max' => 50],
            [['link'], 'string', 'max' => 255],
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
            'store_id' => '店铺id',
            'title' => '标题',
            'link' => '外链',
            'view' => '浏览量',
            'sort' => '优先级',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
