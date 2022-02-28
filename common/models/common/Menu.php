<?php

namespace common\models\common;

use Yii;
use common\traits\Tree;
use common\helpers\TreeHelper;
use common\models\base\BaseModel;

/**
 * This is the model class for table "{{%common_menu}}".
 *
 * @property int $id
 * @property string|null $title 标题
 * @property string|null $name 标识
 * @property string|null $app_id 应用
 * @property int|null $is_addon 是否插件
 * @property string|null $addon_name 插件名称
 * @property string|null $addon_location 插件显示位置
 * @property int|null $cate_id 分类id
 * @property int|null $pid 上级id
 * @property string|null $url 路由
 * @property string|null $icon 样式
 * @property int|null $level 级别
 * @property int|null $dev 开发者[0:都可见;开发模式可见]
 * @property int|null $sort 排序
 * @property string|null $params 参数
 * @property string|null $pattern 开发可见模式
 * @property string|null $tree 树
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 添加时间
 * @property int|null $updated_at 修改时间
 */
class Menu extends BaseModel
{
    use Tree;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['name'], 'unique'],
            [['is_addon', 'cate_id', 'pid', 'level', 'dev', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['params', 'pattern'], 'safe'],
            [['title', 'icon', 'name', 'addon_location'], 'string', 'max' => 50],
            [['app_id'], 'string', 'max' => 20],
            [['addon_name'], 'string', 'max' => 200],
            [['url'], 'string', 'max' => 100],
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
            'is_addon' => '是否插件',
            'addon_name' => '插件名称',
            'addon_location' => '插件显示位置',
            'cate_id' => '分类id',
            'pid' => '父级',
            'url' => '路由',
            'icon' => '图标',
            'level' => '级别',
            'dev' => '开发者可见',
            'sort' => '排序',
            'params' => '参数',
            'pattern' => '适用模式',
            'tree' => '树',
            'status' => '状态',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param Menu $parent
     */
    public function setParent(Menu $parent)
    {
        $this->parent = $parent;
    }

    /**
     * 关联分类
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(MenuCate::class, ['id' => 'cate_id']);
    }

    public function beforeSave($insert)
    {
        if ($this->cate_id > 0 && ($cate = Yii::$app->services->menuCate->findById($this->cate_id))) {
            $this->addon_location = $cate->addon_location;
            $this->addon_name = $cate->addon_name;
            $this->is_addon = $cate->is_addon;
        }

        // 处理上下级关系
        $this->autoUpdateTree();

        return parent::beforeSave($insert);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        /** @var self $parent */
        if ($insert == false && !empty($parent = $this->parent)) {
            self::updateAll(['cate_id' => $parent->cate_id], ['like', 'tree', $parent->tree . TreeHelper::prefixTreeKey($parent->id) . '%', false]);
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
