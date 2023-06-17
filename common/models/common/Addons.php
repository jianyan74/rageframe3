<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%common_addons}}".
 *
 * @property int $id 主键
 * @property string $title 中文名
 * @property string $name 插件名或标识
 * @property string $title_initial 首字母拼音
 * @property string|null $bootstrap 启用文件
 * @property string|null $service 服务调用类
 * @property string|null $cover 封面
 * @property string|null $group 组别
 * @property string|null $brief_introduction 简单介绍
 * @property string|null $description 插件描述
 * @property string|null $author 作者
 * @property string|null $version 版本号
 * @property int|null $is_merchant_route_map 商户路由映射
 * @property string|null $default_config 默认配置
 * @property string|null $console 控制台
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Addons extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_addons}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'name', 'brief_introduction', 'author', 'version'], 'required'],
            [['is_merchant_route_map', 'status', 'created_at', 'updated_at'], 'integer'],
            [['default_config', 'console'], 'safe'],
            [['title', 'group', 'version'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 100],
            [['title_initial'], 'string', 'max' => 1],
            [['bootstrap', 'service'], 'string', 'max' => 255],
            [['cover'], 'string', 'max' => 200],
            [['brief_introduction'], 'string', 'max' => 140],
            [['description'], 'string', 'max' => 1000],
            [['author'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'title' => '插件名称',
            'name' => '插件标识',
            'title_initial' => '首字母拼音',
            'bootstrap' => '启用文件',
            'service' => '服务调用类',
            'cover' => '封面',
            'group' => '组别',
            'brief_introduction' => '简单介绍',
            'description' => '插件描述',
            'author' => '作者',
            'version' => '版本号',
            'is_merchant_route_map' => '商户路由映射',
            'default_config' => '默认配置',
            'console' => '控制台',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfig()
    {
        return $this->hasOne(AddonsConfig::class, ['addon_name' => 'name']);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        // 写入缓存数据
         Yii::$app->services->addons->updateCacheByName($this->name);
        // 更新菜单和菜单分类显示
        Yii::$app->services->menu->updateStatusByAddonName($this->name, $this->status);
        Yii::$app->services->menuCate->updateStatusByAddonName($this->name, $this->status);

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * 卸载插件的时候清理安装的信息
     */
    public function afterDelete()
    {
        AddonsConfig::deleteAll(['addon_name' => $this->name]);
        // 卸载权限
        Yii::$app->services->rbacAuthItem->delByAddonName($this->name);
        // 卸载菜单分类
        Yii::$app->services->menuCate->delByAddonName($this->name);
        // 卸载菜单
        Yii::$app->services->menu->delByAddonName($this->name);
        // 写入缓存数据
        Yii::$app->services->addons->updateCacheByName($this->name);

        parent::afterDelete();
    }
}
