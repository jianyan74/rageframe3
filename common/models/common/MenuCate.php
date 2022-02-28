<?php

namespace common\models\common;

use Yii;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use common\helpers\TreeHelper;
use common\traits\Tree;
use common\models\base\BaseModel;

/**
 * This is the model class for table "{{%common_menu_cate}}".
 *
 * @property int $id 主键
 * @property string $title 标题
 * @property string $app_id 应用
 * @property string|null $icon icon
 * @property int|null $type 应用中心
 * @property int|null $is_default_show 默认显示
 * @property int|null $is_addon 是否插件
 * @property string|null $addon_name 插件名称
 * @property string|null $addon_location 插件显示位置
 * @property int|null $sort 排序
 * @property int|null $level 级别
 * @property string|null $tree 树
 * @property int|null $pid 上级id
 * @property string|null $pattern 开发可见模式
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 添加时间
 * @property int|null $updated_at 修改时间
 */
class MenuCate extends BaseModel
{
    use Tree;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_menu_cate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['name'], 'unique'],
            [['type', 'is_default_show', 'is_addon', 'sort', 'level', 'pid', 'status', 'created_at', 'updated_at'], 'integer'],
            [['pattern'], 'safe'],
            [['title', 'addon_location', 'icon'], 'string', 'max' => 50],
            [['app_id'], 'string', 'max' => 20],
            [['addon_name'], 'string', 'max' => 200],
            [['tree'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'name' => '标识',
            'app_id' => '应用',
            'icon' => 'icon',
            'type' => '应用中心',
            'is_default_show' => '默认显示',
            'is_addon' => '是否插件',
            'addon_name' => '插件名称',
            'addon_location' => '插件显示位置',
            'sort' => '排序',
            'level' => '级别',
            'tree' => '树',
            'pid' => '上级id',
            'pattern' => '适用模式',
            'status' => '状态',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->is_default_show == StatusEnum::ENABLED) {
            self::updateAll(['is_default_show' => StatusEnum::DISABLED], ['is_default_show' => StatusEnum::ENABLED, 'app_id' => $this->app_id]);
        }

        if ($this->isNewRecord) {
            !$this->app_id && $this->app_id = Yii::$app->id;
            !$this->is_addon && $this->is_addon = WhetherEnum::DISABLED;
            $this->pid == 0 && $this->tree = TreeHelper::defaultTreeKey();
        }

        return parent::beforeSave($insert);
    }
}
