<?php

namespace common\models\member;

use Yii;
use common\traits\HasOneMember;

/**
 * This is the model class for table "{{%member_tag_map}}".
 *
 * @property int $tag_id 标签id
 * @property int $member_id 会员id
 */
class TagMap extends \yii\db\ActiveRecord
{
    use HasOneMember;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_tag_map}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag_id', 'member_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => '标签ID',
            'member_id' => '会员ID',
        ];
    }
}
