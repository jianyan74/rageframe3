<?php

namespace addons\WechatMini\common\models\video\common;

/**
 * This is the model class for table "{{%addon_wechat_capabilities_promoter}}".
 *
 * @property int $merchant_id 商户ID
 * @property string $finder_nickname 推广员昵称
 * @property string $promoter_id 推广员ID
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Promoter extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_mini_video_promoter}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['finder_nickname', 'promoter_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'merchant_id' => '商户ID',
            'finder_nickname' => '推广员昵称',
            'promoter_id' => '推广员ID',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
