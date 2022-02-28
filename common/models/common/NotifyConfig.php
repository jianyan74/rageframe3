<?php

namespace common\models\common;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%common_notify_config}}".
 *
 * @property int $id
 * @property int|null $member_id 用户id
 * @property int|null $merchant_id 商户id
 * @property string|null $app_id 应用id
 * @property string|null $name 标识
 * @property string|null $title 标题
 * @property string|null $content 内容
 * @property string|null $type 发送类型
 * @property string|null $template_id 模板ID
 * @property string|null $url 跳转地址
 * @property string|null $params 参数
 * @property int|null $is_addon 是否插件
 * @property string|null $addon_name 插件名称
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class NotifyConfig extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_notify_config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'merchant_id', 'is_addon', 'status', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['params'], 'safe'],
            [['app_id', 'type'], 'string', 'max' => 50],
            [['name', 'title', 'template_id'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 255],
            [['addon_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户id',
            'merchant_id' => '商户id',
            'app_id' => '应用id',
            'name' => '标识',
            'title' => '标题',
            'content' => '内容',
            'type' => '发送类型',
            'template_id' => '模板ID',
            'url' => '跳转地址',
            'params' => '参数',
            'is_addon' => '是否插件',
            'addon_name' => '插件名称',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
