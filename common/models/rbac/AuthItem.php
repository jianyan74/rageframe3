<?php

namespace common\models\rbac;

use common\traits\Tree;

/**
 * This is the model class for table "{{%rbac_auth_item}}".
 *
 * @property int $id
 * @property string $name 别名
 * @property string|null $title 标题
 * @property string $app_id 应用
 * @property int|null $pid 父级id
 * @property int|null $level 级别
 * @property int|null $is_addon 是否插件
 * @property string|null $addon_name 插件名称
 * @property int|null $sort 排序
 * @property string|null $tree 树
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class AuthItem extends \common\models\base\BaseModel
{
    use Tree;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%rbac_auth_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'name'], 'required'],
            [['name'], 'uniqueName'],
            [['pid', 'level', 'is_addon', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['title', 'addon_name'], 'string', 'max' => 200],
            [['app_id'], 'string', 'max' => 20],
            [['tree'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '别名',
            'title' => '标题',
            'app_id' => '应用',
            'pid' => '父级',
            'level' => '级别',
            'is_addon' => '是否插件',
            'addon_name' => '插件名称',
            'sort' => '排序',
            'tree' => '树',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param AuthItem $parent
     */
    public function setParent(AuthItem $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @param $attribute
     */
    public function uniqueName($attribute)
    {
        $model = self::find()
            ->where(['name' => $this->name, 'app_id' => $this->app_id])
            ->andFilterWhere(['addon_name' => $this->addon_name])
            ->one();

        if ($model && $model->id != $this->id) {
            $this->addError($attribute, '别名已存在');
        }
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!$this->isNewRecord) {
            AuthItemChild::updateAll(['name' => $this->name], ['item_id' => $this->id]);
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
