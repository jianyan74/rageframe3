<?php

namespace common\models\member;

use Yii;
use common\enums\StatusEnum;

/**
 * This is the model class for table "{{%member_address}}".
 *
 * @property int $id 主键
 * @property int|null $merchant_id 商户id
 * @property int|null $member_id 用户id
 * @property string|null $realname 真实姓名
 * @property string|null $mobile 手机号码
 * @property int|null $province_id 省
 * @property int|null $city_id 市
 * @property int|null $area_id 区
 * @property string|null $name 省市区名称
 * @property string|null $details 详细地址
 * @property string|null $street_number 门牌号
 * @property string|null $longitude 经度
 * @property string|null $latitude 纬度
 * @property int|null $floor_level 楼层
 * @property string|null $zip_code 邮编
 * @property string|null $tel_no 家庭号码
 * @property int|null $is_default 默认地址
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Address extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_address}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['area_id', 'details', 'realname', 'mobile'], 'required'],
            [['merchant_id', 'member_id', 'province_id', 'city_id', 'area_id', 'floor_level', 'is_default', 'status', 'created_at', 'updated_at'], 'integer'],
            [['realname', 'longitude', 'latitude'], 'string', 'max' => 100],
            [['mobile', 'tel_no'], 'string', 'max' => 20],
            [['name', 'details', 'street_number'], 'string', 'max' => 200],
            [['zip_code'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'merchant_id' => '商户id',
            'member_id' => '用户id',
            'realname' => '真实姓名',
            'mobile' => '手机号码',
            'province_id' => '省',
            'city_id' => '市',
            'area_id' => '区',
            'name' => '省市区名称',
            'details' => '详细地址',
            'street_number' => '门牌号',
            'longitude' => '经度',
            'latitude' => '纬度',
            'floor_level' => '楼层',
            'zip_code' => '邮编',
            'tel_no' => '家庭号码',
            'is_default' => '默认地址',
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
        list($this->province_id, $this->city_id, $this->area_id) = Yii::$app->services->provinces->getParentIdsByAreaId($this->area_id);
        $this->name = Yii::$app->services->provinces->getCityListName([$this->province_id, $this->city_id, $this->area_id]);
        if (($this->isNewRecord || $this->oldAttributes['is_default'] == StatusEnum::DISABLED) && $this->is_default == StatusEnum::ENABLED) {
            self::updateAll(['is_default' => StatusEnum::DISABLED], ['member_id' => $this->member_id, 'is_default' => StatusEnum::ENABLED]);
        }

        return parent::beforeSave($insert);
    }
}
