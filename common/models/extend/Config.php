<?php

namespace common\models\extend;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%extend_config}}".
 *
 * @property int $id 主键
 * @property int|null $merchant_id 商户id
 * @property string|null $title 配置标题
 * @property string|null $name 配置标识
 * @property string|null $type 配置类型
 * @property string|null $remark 说明
 * @property string|null $data 配置
 * @property int|null $sort 排序
 * @property int|null $extend 扩展字段
 * @property int|null $is_addon 是否插件
 * @property string|null $addon_name 插件名称
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Config extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%extend_config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['merchant_id', 'sort', 'extend', 'is_addon', 'status', 'created_at', 'updated_at'], 'integer'],
            [['data'], 'safe'],
            [['title', 'name'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 30],
            [['remark'], 'string', 'max' => 1000],
            [['addon_name'], 'string', 'max' => 200],
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
            'title' => '配置标题',
            'name' => '配置标识',
            'type' => '配置类型',
            'remark' => '备注',
            'data' => '配置',
            'sort' => '排序',
            'extend' => '扩展字段',
            'is_addon' => '是否插件',
            'addon_name' => '插件名称',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
