<?php

namespace addons\Wechat\common\models;

use Yii;
use common\behaviors\MerchantStoreBehavior;
use common\models\base\BaseModel;
use addons\Wechat\common\enums\QrcodeModelTypeEnum;

/**
 * This is the model class for table "{{%addon_wechat_qrcode}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户ID
 * @property int|null $store_id 店铺ID
 * @property string|null $name 场景名称
 * @property string|null $keyword 关联关键字
 * @property int|null $scene_id 场景ID
 * @property string|null $scene_str 场景值
 * @property int|null $model_type 类型
 * @property string|null $ticket ticket
 * @property int|null $expire_seconds 有效期
 * @property int|null $scan_num 扫描次数
 * @property string|null $type 二维码类型
 * @property string|null $url url
 * @property int|null $end_time 结束时间
 * @property int|null $is_addon 是否插件
 * @property string|null $addon_name 插件名称
 * @property string|null $extend 扩展
 * @property int|null $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Qrcode extends BaseModel
{
    use MerchantStoreBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_qrcode}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'model_type'], 'required'],
            [
                [
                    'merchant_id',
                    'store_id',
                    'scene_id',
                    'model_type',
                    'expire_seconds',
                    'scan_num',
                    'end_time',
                    'is_addon',
                    'status',
                    'created_at',
                    'updated_at',
                ],
                'integer',
            ],
            [['name'], 'string', 'max' => 50],
            [['keyword'], 'string', 'max' => 100],
            [['scene_str'], 'string', 'max' => 64],
            [['ticket', 'addon_name'], 'string', 'max' => 200],
            [['type'], 'string', 'max' => 10],
            [['url'], 'string', 'max' => 80],
            [['extend'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户ID',
            'store_id' => '店铺ID',
            'name' => '场景名称',
            'keyword' => '关联关键字',
            'scene_id' => '场景ID',
            'scene_str' => '场景值',
            'model_type' => '类型',
            'ticket' => 'ticket',
            'expire_seconds' => '有效期',
            'scan_num' => '扫描次数',
            'type' => '二维码类型',
            'url' => 'url',
            'end_time' => '结束时间',
            'is_addon' => '是否插件',
            'addon_name' => '插件名称',
            'extend' => '扩展',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 验证提交的类别
     */
    public function verifyModel()
    {
        if ($this->isNewRecord) {
            // 临时
            if ($this->model == QrcodeModelTypeEnum::TEM) {
                empty($this->expire_seconds) && $this->addError('expire_seconds', '临时二维码过期时间必填');
            } else {
                !$this->scene_str && $this->addError('scene_str', '永久二维码场景字符串必填');

                if (self::find()->where(['scene_str' => $this->scene_str, 'merchant_id' => $this->merchant_id])->one()) {
                    $this->addError('scene_str', '场景值已经存在');
                }
            }
        }
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->end_time = time() + (int) $this->expire_seconds;
            $this->addon_name = Yii::$app->params['addon']['name'] ?? '';
            !empty($this->addon_name) && $this->is_addon = 1;
        }

        return parent::beforeSave($insert);
    }
}
