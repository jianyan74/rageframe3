<?php

namespace addons\Wechat\common\models;

use common\behaviors\MerchantBehavior;
use addons\Wechat\common\enums\AttachmentTypeEnum;

/**
 * This is the model class for table "{{%addon_wechat_attachment}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property string|null $file_name 文件原始名
 * @property string|null $local_url 本地地址
 * @property string|null $media_type 类别
 * @property string|null $media_id 微信资源ID
 * @property string|null $media_url 资源Url
 * @property int|null $width 宽度
 * @property int|null $height 高度
 * @property int|null $year 年份
 * @property int|null $month 月份
 * @property int|null $day 日
 * @property string|null $description 视频描述
 * @property string|null $is_temporary 类型[临时:tmp永久:perm]
 * @property int|null $link_type 1微信2本地
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Attachment extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_attachment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'width', 'height', 'year', 'month', 'day', 'link_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['file_name', 'local_url'], 'string', 'max' => 150],
            [['media_type'], 'string', 'max' => 15],
            [['media_id'], 'string', 'max' => 200],
            [['media_url'], 'string', 'max' => 5000],
            [['description'], 'string', 'max' => 200],
            [['is_temporary'], 'string', 'max' => 10],
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
            'store_id' => '店铺ID',
            'file_name' => '文件原始名',
            'local_url' => '本地地址',
            'media_type' => '类别',
            'media_id' => '微信资源ID',
            'media_url' => '资源Url',
            'width' => '宽度',
            'height' => '高度',
            'year' => '年份',
            'month' => '月份',
            'day' => '日',
            'description' => '视频描述',
            'is_temporary' => '类型[临时:tmp永久:perm]',
            'link_type' => '1微信2本地',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 关联图文
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(AttachmentNews::class, ['attachment_id' => 'id']);
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function afterDelete()
    {
        AttachmentNews::deleteAll(['attachment_id' => $this->id]);

        if ($this->media_type == AttachmentTypeEnum::NEWS) {
            Rule::deleteAll(['module' => $this->media_type, 'data' => $this->id]);
            // MassRecord::deleteAll(['module' => $this->media_type, 'data' => $this->id]);
        } else {
            Rule::deleteAll(['module' => $this->media_type, 'data' => $this->media_type]);
            // MassRecord::deleteAll(['module' => $this->media_type, 'data' => $this->media_type]);
        }

        parent::afterDelete();
    }
}
