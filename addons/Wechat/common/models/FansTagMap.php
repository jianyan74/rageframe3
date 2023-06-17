<?php

namespace addons\Wechat\common\models;

use Yii;

/**
 * This is the model class for table "{{%addon_wechat_fans_tag_map}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property int $fans_id 粉丝id
 * @property int $tag_id 标签id
 */
class FansTagMap extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_fans_tag_map}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'fans_id', 'tag_id'], 'integer'],
            [['fans_id', 'tag_id'], 'unique', 'targetAttribute' => ['fans_id', 'tag_id']],
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
            'store_id' => '店铺ID',
            'fans_id' => '粉丝id',
            'tag_id' => '标签id',
        ];
    }
}
