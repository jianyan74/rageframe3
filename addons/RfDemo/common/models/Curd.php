<?php

namespace addons\RfDemo\common\models;

use common\behaviors\MerchantBehavior;
use common\helpers\StringHelper;

/**
 * This is the model class for table "{{%addon_demo_curd}}".
 *
 * @property int $id ID
 * @property int|null $merchant_id 商户id
 * @property int $member_id 管理员ID
 * @property string $title 标题
 * @property int|null $cate_id 分类
 * @property int|null $sort 排序
 * @property int $gender 性别1男2女
 * @property string $content 内容
 * @property string $tag 标签
 * @property string $cover 图片
 * @property string $covers 图片组
 * @property string $file 文件
 * @property string $files 文件组
 * @property string $keywords 关键字
 * @property string $description 描述
 * @property float $price 价格
 * @property int $views 点击
 * @property int|null $start_time 开始时间
 * @property int|null $end_time 结束时间
 * @property string|null $email 邮箱
 * @property int|null $province_id 省
 * @property int|null $city_id 市
 * @property int|null $area_id 区
 * @property string|null $ip ip
 * @property string|null $date 日期
 * @property string|null $time 时间
 * @property string|null $color 颜色
 * @property string|null $longitude 经纬度
 * @property string|null $latitude 经纬度
 * @property string|null $map_overlay 地图范围
 * @property int|null $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Curd extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_demo_curd}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['merchant_id', 'member_id', 'cate_id', 'sort', 'gender', 'views', 'province_id', 'city_id', 'area_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['content', 'covers', 'files'], 'required'],
            [['content'], 'string'],
            [['covers', 'files', 'date', 'multiple_input', 'cate_ids', 'start_time', 'end_time'], 'safe'],
            [['price'], 'number'],
            [['title', 'ip'], 'string', 'max' => 50],
            [['tag', 'cover', 'file', 'keywords'], 'string', 'max' => 100],
            [['description', 'head_portrait'], 'string', 'max' => 200],
            [['email'], 'string', 'max' => 60],
            [['time'], 'string', 'max' => 20],
            [['color'], 'string', 'max' => 7],
            [['longitude', 'latitude'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户id',
            'member_id' => '管理员ID',
            'title' => '标题',
            'cate_id' => '分类',
            'cate_ids' => '分类组',
            'sort' => '排序',
            'gender' => '性别',
            'head_portrait' => '头像',
            'content' => '内容',
            'tag' => '单选标签',
            'multiple_input' => '多输入框',
            'cover' => '单图',
            'covers' => '多图',
            'file' => '单文件',
            'files' => '多文件',
            'keywords' => '关键字',
            'description' => '描述',
            'price' => '价格',
            'views' => '点击',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'email' => '邮箱',
            'province_id' => '省',
            'city_id' => '市',
            'area_id' => '区',
            'ip' => 'ip',
            'date' => '日期',
            'time' => '时间',
            'color' => '颜色',
            'longitude' => '经纬度',
            'latitude' => '经纬度',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(Cate::class, ['id' => 'cate_id']);
    }

    public function beforeSave($insert)
    {
        $this->start_time = StringHelper::dateToInt($this->start_time);
        $this->end_time = StringHelper::dateToInt($this->end_time);

        return parent::beforeSave($insert);
    }
}
