<?php

namespace addons\TinyBlog\common\models;

use common\behaviors\MerchantStoreBehavior;
use common\helpers\StringHelper;

/**
 * This is the model class for table "{{%addon_tiny_blog_adv}}".
 *
 * @property int $id 序号
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺id
 * @property string $name 标题
 * @property string|null $cover 图片
 * @property int|null $location_id 广告位ID
 * @property string|null $silder_text 图片描述
 * @property int|null $start_time 开始时间
 * @property int|null $end_time 结束时间
 * @property string|null $jump_link 跳转链接
 * @property int|null $jump_type 跳转方式[1:新标签; 2:当前页]
 * @property int|null $sort 优先级
 * @property int|null $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Adv extends \common\models\base\BaseModel
{
    use MerchantStoreBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_tiny_blog_adv}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'location_id', 'jump_type', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['name', 'start_time', 'end_time'], 'required'],
            [['start_time', 'end_time'], 'safe'],
            [['cover'], 'string', 'max' => 100],
            [['silder_text'], 'string', 'max' => 150],
            [['jump_link'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '序号',
            'merchant_id' => '商户id',
            'store_id' => '店铺id',
            'name' => '标题',
            'cover' => '图片',
            'location_id' => '广告位ID',
            'silder_text' => '图片描述',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'jump_link' => '跳转链接',
            'jump_type' => '跳转方式', // [1:新标签; 2:当前页]
            'sort' => '优先级',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->start_time = StringHelper::dateToInt($this->start_time);
        $this->end_time = StringHelper::dateToInt($this->end_time);

        return parent::beforeSave($insert);
    }
}
