<?php

namespace common\models\common;

/**
 * This is the model class for table "{{%common_config}}".
 *
 * @property int $id 主键
 * @property string $title 配置标题
 * @property string $name 配置标识
 * @property string $app_id 应用
 * @property string $type 配置类型
 * @property int $cate_id 配置分类
 * @property string $extra 配置值
 * @property string $remark 配置说明
 * @property int|null $is_hide_remark 是否隐藏说明
 * @property string|null $default_value 默认配置
 * @property int|null $sort 排序
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Config extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'name', 'type', 'cate_id'], 'required'],
            [['cate_id', 'is_hide_remark', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'name'], 'string', 'max' => 50],
            [['app_id'], 'string', 'max' => 20],
            [['type'], 'string', 'max' => 30],
            [['extra', 'remark'], 'string', 'max' => 1000],
            [['default_value'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'title' => '配置标题',
            'name' => '配置标识',
            'app_id' => '应用',
            'type' => '配置类型',
            'cate_id' => '配置分类',
            'extra' => '配置值',
            'remark' => '配置说明',
            'is_hide_remark' => '是否隐藏说明',
            'default_value' => '默认配置',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(ConfigCate::class, ['id' => 'cate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValue()
    {
        return $this->hasOne(ConfigValue::class, ['config_id' => 'id']);
    }
}
