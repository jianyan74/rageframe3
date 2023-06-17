<?php

namespace addons\TinyBlog\common\models;

/**
 * This is the model class for table "{{%addon_tiny_blog_tag_map}}".
 *
 * @property int|null $tag_id 标签id
 * @property int|null $article_id 文章id
 */
class TagMap extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_tiny_blog_tag_map}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag_id', 'article_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => '标签id',
            'article_id' => '文章id',
        ];
    }
}
