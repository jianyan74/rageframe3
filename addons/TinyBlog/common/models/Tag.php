<?php

namespace addons\TinyBlog\common\models;

use common\behaviors\MerchantStoreBehavior;

/**
 * This is the model class for table "{{%addon_tiny_blog_tag}}".
 *
 * @property int $id 主键
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺id
 * @property string $title 标题
 * @property int|null $sort 排序
 * @property int|null $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Tag extends \common\models\base\BaseModel
{
    use MerchantStoreBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_tiny_blog_tag}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'frequency', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 30],
            [['title', 'sort'], 'required'],
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
            'frequency' => '使用次数',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 关联中间表
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTagMap()
    {
        return $this->hasOne(TagMap::class, ['tag_id' => 'id']);
    }
}
