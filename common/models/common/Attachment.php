<?php

namespace common\models\common;

use common\behaviors\MerchantStoreBehavior;

/**
 * This is the model class for table "{{%common_attachment}}".
 *
 * @property int $id
 * @property int|null $member_id 用户
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 门店ID
 * @property int|null $cate_id 分类
 * @property string|null $drive 驱动
 * @property string|null $upload_type 上传类型
 * @property string $specific_type 类别
 * @property string|null $url url
 * @property string|null $path 本地路径
 * @property string|null $md5 md5校验码
 * @property string|null $name 文件原始名
 * @property string|null $extension 扩展名
 * @property int|null $size 长度
 * @property int|null $format_size 长度
 * @property int|null $year 年份
 * @property int|null $month 月份
 * @property int|null $day 日
 * @property int|null $width 宽度
 * @property int|null $height 高度
 * @property string|null $ip 上传者ip
 * @property string|null $req_id 对外id
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Attachment extends \common\models\base\BaseModel
{
    use MerchantStoreBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_attachment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['member_id', 'merchant_id', 'store_id', 'cate_id', 'size', 'year', 'month', 'day', 'width', 'height', 'status', 'created_at', 'updated_at'], 'integer'],
            [['drive', 'req_id', 'format_size'], 'string', 'max' => 50],
            [['upload_type'], 'string', 'max' => 10],
            [['specific_type', 'md5', 'extension'], 'string', 'max' => 100],
            [['url', 'path', 'name'], 'string', 'max' => 500],
            [['ip'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户',
            'merchant_id' => '商户id',
            'store_id' => '门店ID',
            'cate_id' => '所属分组',
            'drive' => '驱动',
            'upload_type' => '上传类型',
            'specific_type' => '类别',
            'url' => 'Url 地址',
            'path' => '本地路径',
            'md5' => 'md5校验码',
            'name' => '文件名', // 文件原始名
            'extension' => '扩展名',
            'size' => '大小',
            'format_size' => '大小',
            'year' => '年份',
            'month' => '月份',
            'day' => '日',
            'width' => '宽度',
            'height' => '高度',
            'ip' => '上传者ip',
            'req_id' => '对外id',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(AttachmentCate::class, ['id' => 'cate_id']);
    }
}
