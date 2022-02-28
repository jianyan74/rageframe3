<?php

namespace common\models\common;

use common\enums\StatusEnum;
use common\traits\Tree;

/**
 * This is the model class for table "{{%common_config_cate}}".
 *
 * @property int $id 主键
 * @property string $title 标题
 * @property string|null $name 标识
 * @property int|null $pid 上级id
 * @property string $app_id 应用
 * @property int|null $level 级别
 * @property int|null $sort 排序
 * @property string $tree 树
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 添加时间
 * @property int|null $updated_at 修改时间
 */
class ConfigCate extends \common\models\base\BaseModel
{
    use Tree;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_config_cate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['name'], 'unique'],
            [['title', 'name'], 'trim'],
            [['pid', 'level', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['app_id'], 'string', 'max' => 20],
            [['tree'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'title' => '标题',
            'name' => '标识',
            'pid' => '父级',
            'app_id' => '应用',
            'level' => '级别',
            'sort' => '排序',
            'tree' => '树',
            'status' => '状态',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfig()
    {
        return $this->hasMany(Config::class, ['cate_id' => 'id'])
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy('sort asc');
    }
}
