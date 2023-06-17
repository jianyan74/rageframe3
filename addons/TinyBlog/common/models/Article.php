<?php

namespace addons\TinyBlog\common\models;

use common\helpers\StringHelper;
use common\traits\HasOneMerchant;
use common\behaviors\MerchantStoreBehavior;

/**
 * This is the model class for table "{{%addon_tiny_blog_article}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺id
 * @property string $title 标题
 * @property string|null $cover 封面
 * @property string|null $seo_key seo关键字
 * @property string|null $seo_content seo内容
 * @property int|null $cate_id 分类id
 * @property string|null $description 描述
 * @property int $position 推荐位
 * @property string|null $content 文章内容
 * @property string|null $link 外链
 * @property string|null $author 作者
 * @property int $view 浏览量
 * @property int $sort 优先级
 * @property int|null $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Article extends \yii\db\ActiveRecord
{
    use MerchantStoreBehavior, HasOneMerchant;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_tiny_blog_article}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'cate_id', 'view', 'sort', 'status'], 'integer'],
            [['title', 'author', 'cate_id', 'content', 'created_at'], 'required'],
            [['content'], 'string'],
            [['created_at', 'updated_at', 'position'], 'safe'],
            [['title', 'seo_key'], 'string', 'max' => 50],
            [['cover'], 'string', 'max' => 255],
            [['seo_content'], 'string', 'max' => 1000],
            [['description'], 'string', 'max' => 140],
            [['link'], 'string', 'max' => 255],
            [['author'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户id',
            'store_id' => '店铺id',
            'title' => '标题',
            'cover' => '封面',
            'seo_key' => 'seo关键字',
            'seo_content' => 'seo内容',
            'cate_id' => '分类',
            'description' => '描述',
            'position' => '推荐位',
            'content' => '文章内容',
            'link' => '原文链接',
            'author' => '作者',
            'view' => '浏览量',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 关联分类
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(Cate::class, ['id' => 'cate_id']);
    }

    /**
     * 关联标签
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTagMap()
    {
        return $this->hasOne(TagMap::class, ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])
            ->viaTable(TagMap::tableName(), ['article_id' => 'id'])
            ->asArray();
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->created_at = StringHelper::dateToInt($this->created_at);
        $this->updated_at = time();

        return parent::beforeSave($insert);
    }
}
