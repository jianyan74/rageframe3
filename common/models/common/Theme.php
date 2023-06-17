<?php

namespace common\models\common;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%common_theme}}".
 *
 * @property int $id 主键
 * @property int $merchant_id 商户ID
 * @property int|null $member_id 用户ID
 * @property int|null $member_type 用户类型
 * @property string $app_id 应用
 * @property string|null $layout 布局类型
 * @property string|null $color 主题颜色
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 添加时间
 * @property int|null $updated_at 修改时间
 */
class Theme extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%common_theme}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'member_id', 'member_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['app_id'], 'string', 'max' => 20],
            [['layout', 'color'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'merchant_id' => '商户ID',
            'member_id' => '用户ID',
            'member_type' => '用户类型',
            'app_id' => '应用',
            'layout' => '布局类型',
            'color' => '主题颜色',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }
}
