<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%common_config_value}}".
 *
 * @property int $id 主键
 * @property string $app_id 应用
 * @property int $config_id 配置id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property string|null $data 配置内
 */
class ConfigValue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_config_value}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['config_id', 'merchant_id', 'store_id'], 'integer'],
            [['data'], 'string'],
            [['app_id'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'app_id' => '应用',
            'config_id' => '配置id',
            'merchant_id' => '商户id',
            'store_id' => '店铺ID',
            'data' => '配置内',
        ];
    }
}
