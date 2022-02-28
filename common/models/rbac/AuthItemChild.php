<?php

namespace common\models\rbac;

use common\enums\StatusEnum;
use Yii;

/**
 * This is the model class for table "{{%rbac_auth_item_child}}".
 *
 * @property int $role_id 角色id
 * @property int $item_id 权限id
 * @property string $name 别名
 * @property string $app_id 类别
 * @property int|null $is_addon 是否插件
 * @property string|null $addon_name 插件名称
 */
class AuthItemChild extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%rbac_auth_item_child}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'item_id', 'is_addon'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['app_id'], 'string', 'max' => 20],
            [['addon_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'role_id' => '角色id',
            'item_id' => '权限id',
            'name' => '别名',
            'app_id' => '类别',
            'is_addon' => '是否插件',
            'addon_name' => '插件名称',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(AuthItem::class, ['name' => 'name'])
            ->orderBy('sort asc, id asc')
            ->where(['status' => StatusEnum::ENABLED]);
    }
}
