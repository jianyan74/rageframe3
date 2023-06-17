<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%common_addons_config}}".
 *
 * @property int $id 主键
 * @property string $app_id 应用
 * @property string $addon_name 插件名或标识
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property string|null $data 配置
 */
class AddonsConfig extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_addons_config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id'], 'integer'],
            [['data'], 'safe'],
            [['app_id'], 'string', 'max' => 20],
            [['addon_name'], 'string', 'max' => 100],
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
            'addon_name' => '插件名或标识',
            'merchant_id' => '商户id',
            'store_id' => '店铺ID',
            'data' => '配置',
        ];
    }
}
