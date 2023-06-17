<?php

namespace common\models\merchant;

use Yii;
use common\helpers\RegularHelper;
use common\helpers\StringHelper;

/**
 * This is the model class for table "{{%merchant}}".
 *
 * @property int $id
 * @property string|null $title 店铺名称
 * @property int|null $operating_type 运营类型
 * @property int|null $cate_id 分类
 * @property float|null $tax_rate 税率
 * @property string|null $logo 店铺logo
 * @property string|null $cover 店铺头像
 * @property int|null $sort 店铺排序
 * @property string|null $brief_introduction 简介
 * @property int|null $term_of_validity_type 有效期类型 0固定时间 1不限
 * @property int|null $start_time 开始时间
 * @property int|null $end_time 结束时间
 * @property string|null $email 邮箱
 * @property int|null $province_id 省
 * @property int|null $city_id 城市
 * @property int|null $area_id 地区
 * @property string|null $address_name 地址
 * @property string|null $address_details 详细地址
 * @property string|null $longitude 经度
 * @property string|null $latitude 纬度
 * @property string|null $contacts 联系人
 * @property string|null $mobile 手机号码
 * @property string|null $tel_no 电话号码
 * @property int|null $level_id 店铺等级
 * @property string|null $keywords 店铺seo关键字
 * @property string|null $description 店铺seo描述
 * @property string|null $environment 环境相册
 * @property string|null $qq QQ
 * @property int|null $credit 店铺信用
 * @property float|null $desc_credit 描述相符度分数
 * @property float|null $service_credit 服务态度分数
 * @property float|null $delivery_credit 发货速度分数
 * @property float|null $sales_money 店铺销售额（不计算退款）
 * @property string|null $business_week 每周营业日期
 * @property string|null $business_time 营业开始时间
 * @property int|null $collect_num 收藏数量
 * @property int|null $comment_num 评价数
 * @property int|null $transmit_num 分享数
 * @property int|null $auth_role_id 开店套餐
 * @property int|null $certification_type 认证类型
 * @property string|null $close_cause 店铺关闭原因
 * @property int|null $audit_status 审核状态
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Merchant extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%merchant}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'cate_id', 'auth_role_id', 'mobile'], 'required'],
            [['operating_type', 'cate_id', 'sort', 'term_of_validity_type', 'province_id', 'city_id', 'area_id', 'level_id', 'credit', 'collect_num', 'comment_num', 'transmit_num', 'auth_role_id', 'certification_type', 'audit_status', 'status', 'created_at', 'updated_at'], 'integer'],
            [['tax_rate', 'desc_credit', 'service_credit', 'delivery_credit', 'sales_money'], 'number'],
            [['environment', 'business_week', 'business_time'], 'safe'],
            [['title', 'address_name'], 'string', 'max' => 200],
            [['logo', 'address_details', 'longitude', 'latitude', 'mobile', 'contacts'], 'string', 'max' => 100],
            [['cover'], 'string', 'max' => 150],
            [['brief_introduction'], 'string', 'max' => 1000],
            [['email'], 'string', 'max' => 60],
            [['email'], 'email'],
            [['start_time', 'end_time'], 'safe'],
            ['mobile', 'match', 'pattern' => RegularHelper::mobile(), 'message' => '请输入正确的手机号'],
            [['tel_no'], 'string', 'max' => 20],
            [['keywords', 'description', 'close_cause'], 'string', 'max' => 255],
            [['qq'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '店铺名称',
            'operating_type' => '运营类型',
            'cate_id' => '主营行业',
            'tax_rate' => '税率',
            'logo' => '商户 Logo',
            'cover' => '商户头像',
            'sort' => '店铺排序',
            'brief_introduction' => '简介',
            'term_of_validity_type' => '有效期类型',
            'start_time' => '开始时间',
            'end_time' => '到期时间',
            'email' => '邮箱',
            'province_id' => '省',
            'city_id' => '城市',
            'area_id' => '地区',
            'address_name' => '地址',
            'address_details' => '详细地址',
            'longitude' => '经度',
            'latitude' => '纬度',
            'contacts' => '联系人',
            'mobile' => '手机号码',
            'tel_no' => '电话号码',
            'level_id' => '店铺等级',
            'keywords' => '店铺关键字',
            'description' => '店铺 seo 描述',
            'environment' => '环境相册',
            'qq' => 'QQ',
            'credit' => '店铺信用',
            'desc_credit' => '描述相符度分数',
            'service_credit' => '服务态度分数',
            'delivery_credit' => '发货速度分数',
            'sales_money' => '店铺销售额',
            'business_week' => '每周营业日期',
            'business_time' => '营业开始时间',
            'collect_num' => '收藏数量',
            'comment_num' => '评价数',
            'transmit_num' => '分享数',
            'auth_role_id' => '开店套餐',
            'certification_type' => '认证类型',
            'close_cause' => '店铺关闭原因',
            'audit_status' => '审核状态',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    public function attributeHints()
    {
        return [
            'keywords' => '多个关键字之间用英文“,”隔开'
        ];
    }

    public function beforeSave($insert)
    {
        $this->start_time = StringHelper::dateToInt($this->start_time);
        $this->end_time = StringHelper::dateToInt($this->end_time);

        if (isset($this->oldAttributes['area_id']) && $this->area_id != $this->oldAttributes['area_id']) {
            $this->address_name = Yii::$app->services->provinces->getCityListName([$this->province_id, $this->city_id, $this->area_id]);
        }

        return parent::beforeSave($insert);
    }
}
