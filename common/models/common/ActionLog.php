<?php

namespace common\models\common;

use Yii;
use common\behaviors\MerchantBehavior;
use common\traits\HasOneMember;

/**
 * This is the model class for table "{{%common_action_log}}".
 *
 * @property int $id 主键
 * @property string|null $app_id 应用id
 * @property int|null $merchant_id 商户id
 * @property int|null $member_id 用户id
 * @property string|null $member_name 操作人
 * @property string|null $method 提交类型
 * @property string|null $controller 控制器
 * @property string|null $module 模块
 * @property string|null $action 方法
 * @property string|null $url 提交url
 * @property string|null $get_data get数据
 * @property string|null $post_data post数据
 * @property string|null $header_data header数据
 * @property string|null $behavior 行为类别
 * @property string|null $remark 日志备注
 * @property int|null $is_addon 是否插件
 * @property string|null $addon_name 插件名称
 * @property int|null $map_id 关联ID
 * @property string|null $map_data 关联数据
 * @property string|null $country 国家
 * @property string|null $provinces 省
 * @property string|null $city 城市
 * @property string|null $device 设备信息
 * @property string|null $ip ip地址
 * @property string|null $req_id 唯一ID
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class ActionLog extends \common\models\base\BaseModel
{
    use MerchantBehavior, HasOneMember;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_action_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'member_id', 'is_addon', 'map_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['get_data', 'post_data', 'header_data', 'map_data'], 'safe'],
            [['app_id', 'action', 'behavior', 'module', 'req_id'], 'string', 'max' => 50],
            [['member_name', 'controller', 'country', 'provinces', 'city'], 'string', 'max' => 100],
            [['method'], 'string', 'max' => 20],
            [['url', 'addon_name', 'device'], 'string', 'max' => 200],
            [['remark'], 'string', 'max' => 1000],
            [['ip'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'app_id' => '所在应用',
            'merchant_id' => '商户id',
            'member_id' => '用户id',
            'member_name' => '操作人',
            'method' => '提交类型',
            'module' => '模块',
            'controller' => '控制器',
            'action' => '方法',
            'url' => 'Url',
            'get_data' => 'get数据',
            'post_data' => 'post数据',
            'header_data' => 'header数据',
            'behavior' => '行为类别',
            'remark' => '日志备注',
            'is_addon' => '是否插件',
            'addon_name' => '插件名称',
            'map_id' => '关联ID',
            'map_data' => '关联数据',
            'country' => '国家',
            'provinces' => '省',
            'city' => '城市',
            'device' => '设备信息',
            'ip' => 'ip地址',
            'req_id' => '唯一ID',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }


    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->req_id = Yii::$app->params['uuid'];
        }

        return parent::beforeSave($insert);
    }
}
