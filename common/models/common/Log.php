<?php

namespace common\models\common;

use common\behaviors\MerchantBehavior;
use common\traits\HasOneMember;

/**
 * This is the model class for table "{{%common_log}}".
 *
 * @property int $id
 * @property string|null $app_id 应用id
 * @property int|null $merchant_id 商户id
 * @property int|null $member_id 用户id
 * @property string|null $member_name 用户id
 * @property string|null $method 提交类型
 * @property string|null $module 模块
 * @property string|null $controller 控制器
 * @property string|null $action 方法
 * @property string|null $url 提交url
 * @property string|null $get_data get数据
 * @property string|null $post_data post数据
 * @property string|null $header_data header数据
 * @property string|null $ip ip地址
 * @property int|null $error_code 报错code
 * @property string|null $error_msg 报错信息
 * @property string|null $error_data 报错日志
 * @property int|null $is_addon 是否插件
 * @property string|null $addon_name 插件名称
 * @property string|null $req_id 对外id
 * @property string|null $device 设备信息
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Log extends \common\models\base\BaseModel
{
    use MerchantBehavior, HasOneMember;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'member_id', 'error_code', 'is_addon', 'status', 'created_at', 'updated_at'], 'integer'],
            [['get_data', 'post_data', 'header_data', 'error_data'], 'safe'],
            [['app_id', 'module', 'action', 'ip', 'req_id'], 'string', 'max' => 50],
            [['member_name', 'controller'], 'string', 'max' => 100],
            [['method'], 'string', 'max' => 20],
            [['url', 'error_msg'], 'string', 'max' => 1000],
            [['addon_name', 'device'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => '所属应用',
            'merchant_id' => '商户id',
            'member_id' => '用户id',
            'member_name' => '用户昵称',
            'method' => '提交类型',
            'module' => '模块',
            'controller' => '控制器',
            'action' => '方法',
            'url' => 'Url',
            'get_data' => 'get数据',
            'post_data' => 'post数据',
            'header_data' => 'header数据',
            'ip' => 'ip地址',
            'error_code' => '报错code',
            'error_msg' => '报错信息',
            'error_data' => '报错日志',
            'is_addon' => '是否插件',
            'addon_name' => '插件名称',
            'req_id' => '对外id',
            'device' => '设备信息',
            'status' => '状态(-1:已删除,0:禁用,1:正常)',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
