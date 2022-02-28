<?php

namespace common\models\member;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%member_tag}}".
 *
 * @property int $id 主键
 * @property int|null $merchant_id 商户id
 * @property string $title 标题
 * @property int|null $sort 排序
 * @property int|null $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Tag extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_tag}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['title'], 'required'],
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
            'title' => '标题',
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

    public function afterDelete()
    {
        TagMap::deleteAll(['tag_id' => $this->id]);

        parent::afterDelete();
    }
}
