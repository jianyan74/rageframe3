<?php

namespace addons\Wechat\common\models;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%addon_wechat_attachment_news}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property int|null $attachment_id 关联的资源id
 * @property string $title 标题
 * @property string|null $thumb_media_id 图文消息的封面图片素材id（必须是永久mediaID）
 * @property string|null $thumb_url 缩略图Url
 * @property string|null $author 作者
 * @property string|null $digest 简介
 * @property int|null $show_cover_pic 0为false，即不显示，1为true，即显示
 * @property string|null $content 图文消息的具体内容，支持HTML标签，必须少于2万字符
 * @property string|null $content_source_url 图文消息的原文地址，即点击“阅读原文”后的URL
 * @property string|null $media_url 资源Url
 * @property int|null $need_open_comment 是否打开评论 0不打开，1打开
 * @property int|null $only_fans_can_comment 是否粉丝才可评论 0所有人可评论，1粉丝才可评论
 * @property int|null $sort 排序
 * @property int|null $year 年份
 * @property int|null $month 月份
 * @property int|null $day 日
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class AttachmentNews extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_attachment_news}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'attachment_id', 'show_cover_pic', 'need_open_comment', 'only_fans_can_comment', 'sort', 'year', 'month', 'day', 'status', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 50],
            [['thumb_url', 'media_url'], 'string', 'max' => 500],
            [['digest', 'content_source_url', 'thumb_media_id'], 'string', 'max' => 200],
            [['author'], 'string', 'max' => 64],
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
            'attachment_id' => '关联的资源id',
            'title' => '标题',
            'thumb_media_id' => '图文消息的封面图片素材id（必须是永久mediaID）',
            'thumb_url' => '缩略图Url',
            'author' => '作者',
            'digest' => '简介',
            'show_cover_pic' => '0为false，即不显示，1为true，即显示',
            'content' => '图文消息的具体内容，支持HTML标签，必须少于2万字符',
            'content_source_url' => '图文消息的原文地址，即点击“阅读原文”后的URL',
            'media_url' => '资源Url',
            'need_open_comment' => '是否打开评论', //  0 不打开，1打开
            'only_fans_can_comment' => '是否粉丝才可评论', //  0 所有人可评论，1粉丝才可评论
            'sort' => '排序',
            'year' => '年份',
            'month' => '月份',
            'day' => '日',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 关联素材
     */
    public function getAttachment()
    {
        return $this->hasOne(Attachment::class, ['id' => 'attachment_id']);
    }
}
