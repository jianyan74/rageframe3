<?php

namespace addons\RfDemo\common\models;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%addon_demo_curd_map}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $curd_id
 * @property string|null $name 名称
 * @property float|null $shipping_fee 运费
 * @property string|null $type 类型
 * @property string|null $path 覆盖范围
 * @property string|null $polygon 覆盖范围
 * @property float|null $radius 半径
 * @property int|null $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class CurdMap extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_demo_curd_map}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'curd_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['shipping_fee', 'radius'], 'number'],
            [['path', 'polygon'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 50],
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
            'curd_id' => 'Curd ID',
            'name' => '名称',
            'shipping_fee' => '运费',
            'type' => '类型',
            'path' => '覆盖范围(默认)',
            'polygon' => '覆盖范围',
            'radius' => '半径',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
