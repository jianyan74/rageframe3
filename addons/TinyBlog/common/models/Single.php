<?php

namespace addons\TinyBlog\common\models;

use common\behaviors\MerchantStoreBehavior;

/**
 * This is the model class for table "{{%addon_tiny_blog_single}}".
 *
 * @property int $id 主键
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺id
 * @property string $title 标题
 * @property string|null $name 标识
 * @property string|null $seo_key seo关键字
 * @property string|null $seo_content seo内容
 * @property string|null $cover 封面
 * @property string|null $description 描述
 * @property string|null $content 文章内容
 * @property string|null $link 外链
 * @property string|null $author 作者
 * @property int $view 浏览量
 * @property int $sort 优先级
 * @property int|null $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Single extends \common\models\base\BaseModel
{
    use MerchantStoreBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_tiny_blog_single}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'view', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'required'],
            [['content'], 'string'],
            [['title', 'seo_key'], 'string', 'max' => 50],
            [['name', 'author'], 'string', 'max' => 40],
            [['seo_content'], 'string', 'max' => 1000],
            [['cover'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 140],
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
            'name' => '标识',
            'seo_key' => 'seo关键字',
            'seo_content' => 'seo内容',
            'cover' => '封面',
            'description' => '描述',
            'content' => '文章内容',
            'link' => '外链',
            'author' => '作者',
            'view' => '浏览量',
            'sort' => '优先级',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
