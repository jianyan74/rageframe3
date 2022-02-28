<?php

namespace common\models\member;

use Yii;

/**
 * This is the model class for table "{{%member_level_config}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $upgrade_type 升级方式
 * @property int|null $auto_upgrade_type 自动升级类型
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class LevelConfig extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_level_config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'upgrade_type', 'auto_upgrade_type', 'status', 'created_at', 'updated_at'], 'integer'],
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
            'upgrade_type' => '升级方式',
            'auto_upgrade_type' => '自动升级类型',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
