<?php

namespace addons\Wechat\services;

use common\helpers\ArrayHelper;
use Yii;
use yii\data\Pagination;
use common\helpers\StringHelper;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\components\Service;
use EasyWeChat\Kernel\Messages\Article;
use addons\Wechat\common\models\Attachment;
use addons\Wechat\common\models\AttachmentNews;
use addons\Wechat\common\enums\AttachmentLinkTypeEnum;
use addons\Wechat\common\enums\AttachmentTypeEnum;
use addons\Wechat\common\enums\AttachmentModelEnum;


/**
 * Class AttachmentService
 * @package addons\Wechat\services
 * @author jianyan74 <751393839@qq.com>
 */
class AttachmentService extends Service
{
    /**
     * 预览方法
     *
     * @var array
     */
    protected $previewActions = [
        AttachmentTypeEnum::TEXT => 'previewText',
        AttachmentTypeEnum::NEWS => 'previewNews',
        AttachmentTypeEnum::VOICE => 'previewVoice',
        AttachmentTypeEnum::IMAGE => 'previewImage',
        AttachmentTypeEnum::VIDEO => 'previewVideo',
        AttachmentTypeEnum::CARD => 'previewCard',
    ];

    /**
     * 基于名称的预览方法
     *
     * @var array
     */
    protected $previewActionsByName = [
        AttachmentTypeEnum::TEXT => 'previewTextByName',
        AttachmentTypeEnum::NEWS => 'previewNewsByName',
        AttachmentTypeEnum::VOICE => 'previewVoiceByName',
        AttachmentTypeEnum::IMAGE => 'previewImageByName',
        AttachmentTypeEnum::VIDEO => 'previewVideoByName',
        AttachmentTypeEnum::CARD => 'previewCardByName',
    ];

    /**
     * 获取资源数据
     *
     * @param string $media_type
     * @param string $year
     * @param string $month
     * @param string $keyword
     * @return array
     */
    public function getListPage($media_type = '', $year = '', $month = '', $keyword = '')
    {
        $data = Attachment::find()
            ->where(['status' => StatusEnum::ENABLED, 'media_type' => $media_type])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andFilterWhere(['year' => $year])
            ->andFilterWhere(['month' => $month])
            ->andFilterWhere(['like', 'file_name', $keyword]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10, 'validatePage' => false]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->asArray()
            ->all();

        $list = [];
        foreach ($models as $model) {
            $listTmp = [];
            $listTmp['key'] = $model['media_id'];
            $listTmp['title'] = $model['file_name'];
            $listTmp['type'] = $model['media_type'];
            $listTmp['imgUrl'] = '';
            if ($media_type == AttachmentTypeEnum::IMAGE) {
                $listTmp['imgUrl'] = Url::to(['analysis/image', 'attach' => $model['media_url']]);
            }

            $list[] = $listTmp;
            unset($listTmp);
        }

        return $list;
    }

    /**
     * 资源预览
     *
     * @param $attach_id
     * @param $sendType
     * @param $content
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function preview($attach_id, $sendType, $content)
    {
        $attachment = $this->findById($attach_id);
        // 1:微信号预览 2:openid预览
        $method = $sendType == 1 ? $this->previewActionsByName[$attachment['media_type']] : $this->previewActions[$attachment['media_type']];
        $result = Yii::$app->wechat->app->broadcasting->$method($attachment['media_id'], $content);
        Yii::$app->services->base->getWechatError($result);
    }

    /**
     * 同步
     *
     * @param string $type 素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）
     * @param int $offset 从全部素材的该偏移位置开始返回，可选，默认 0，0 表示从第一个素材 返回
     * @param int $count 返回素材的数量，可选，默认 20, 取值在 1 到 20 之间
     * @return bool|array
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\db\Exception
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function sync($type, $offset, $count)
    {
        $app = Yii::$app->wechat->app;
        if ($type == AttachmentTypeEnum::NEWS) {
            $lists = Yii::$app->wechat->app->draft->batchGet($offset, $count);
        } else {
            $lists = Yii::$app->wechat->app->material->list($type, $offset, $count);
        }

        // 解析微信接口是否报错.报错则抛出错误信息
        Yii::$app->services->base->getWechatError($lists);
        if (empty($lists)) {
            return true;
        }

        $total = $lists['total_count'];
        // 素材列表
        $list = $lists['item'];
        $addMaterial = [];
        // 系统内的素材
        $systemMaterial = $this->getListByMediaIds(array_column($list, 'media_id'));
        $defaultData = array_column($systemMaterial, 'media_id');

        switch ($type) {
            // ** 图文 **//
            case AttachmentTypeEnum::NEWS :
                foreach ($list as $vo) {
                    if (!in_array($vo['media_id'], $defaultData)) {
                        $attachment = new Attachment();
                        $attachment->media_id = $vo['media_id'];
                        $attachment->media_type = $type;
                        $attachment->is_temporary = AttachmentModelEnum::MODEL_PERM;
                        $attachment->created_at = $vo['update_time'];
                        $attachment->save();

                        // 插入文章
                        foreach ($vo['content']['news_item'] as $key => $news_item) {
                            $news = new AttachmentNews();
                            $news->attributes = $news_item;
                            $news->media_url = $news_item['url'];
                            $news->content = str_replace("data-src", "src", $news->content);
                            $news->attachment_id = $attachment->id;
                            $news->sort = $key;
                            $news->save();
                        }
                    }
                }
                break;
            //** 图片/语音/视频 **//
            default :
                $merchant_id = Yii::$app->services->merchant->getNotNullId();
                foreach ($list as $vo) {
                    if (!in_array($vo['media_id'], $defaultData)) {
                        // 判断是否是视频
                        if (AttachmentTypeEnum::VIDEO == $type) {
                            $detail = $app->material->get($vo['media_id']);
                            $mediaUrl = $detail['down_url'];
                            $mediaDescription = $detail['description'];
                        } else {
                            $mediaUrl = $vo['url'] ?? '';
                            $mediaDescription = '';
                        }

                        $addMaterial[] = [
                            $merchant_id,
                            $vo['name'],
                            $vo['media_id'],
                            $mediaUrl,
                            $mediaDescription,
                            $type,
                            AttachmentModelEnum::MODEL_PERM,
                            $vo['update_time'],
                            time()
                        ];
                    }
                }

                if (!empty($addMaterial)) {
                    // 批量插入数据
                    $field = [
                        'merchant_id',
                        'file_name',
                        'media_id',
                        'media_url',
                        'description',
                        'media_type',
                        'is_temporary',
                        'created_at',
                        'updated_at'
                    ];
                    Yii::$app->db->createCommand()->batchInsert(Attachment::tableName(), $field, $addMaterial)->execute();
                }

                break;
        }

        if ($total - ($offset + $count) > 0) {
            return [
                'offset' => ($offset + $count),
                'count' => $count
            ];
        }

        return true;
    }

    /**
     * @param Attachment $model
     * @param $list
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function editNews(Attachment $model, $list, $isNewRecord)
    {
        $wechatArticleList = [];
        // 获取图片的链接地址
        $localImageUrl = Url::to(['analysis/image']) . "?attach=";
        foreach ($list as $key => &$item) {
            $item['content'] = StringHelper::replace($localImageUrl, '', trim($item['content']));
            $item['thumb_url'] = StringHelper::replace($localImageUrl, '', trim($item['thumb_url']));
            // 上传资源
            list($media_id, $item['thumb_url']) = $this->isUploadWechatByImage($item['thumb_url']);
            !empty($media_id) && $item['thumb_media_id'] = $media_id;;

            // 循环上传文章图片到微信
            preg_match_all('/<img[^>]*src\s*=\s*([\'"]?)([^\'" >]*)\1/isu', $item['content'], $match);
            foreach ($match[2] as $src) {
                // 判断是否已经上传到微信了
                if (strpos(urldecode($src), AttachmentLinkTypeEnum::URL) === false) {
                    $result = Yii::$app->wechat->app->material->uploadArticleImage(StringHelper::getLocalFilePath($src));
                    // 替换图片上传
                    $item['content'] = StringHelper::replace($src, $result['url'], $item['content']);
                }
            }

            $item['content'] = htmlspecialchars_decode($item['content']);

            // 默认微信返回值
            $wechatArticleList[] = $item;
        }

        // 上传到微信
        $news_item = $this->isUploadWechatByNews($model, $wechatArticleList, $isNewRecord);
        // 插入文章到表
        foreach ($list as $k => $vo) {
            $news = Yii::$app->wechatService->attachmentNews->findModel($vo['id'] ?? null);
            $news->attributes = $vo;
            $news->attachment_id = $model->id;
            // 判断是否微信 否则直接拿取图文链接
            if ($model->link_type == AttachmentLinkTypeEnum::WECHAT) {
                $isNewRecord && $news->media_url = $news_item[$k]['url'] ?? '';
            } else {
                $news->media_url = $vo['content_source_url'];
            }

            $news->sort = $k;
            !$news->save() && $this->error($news);
        }
    }

    /**
     * 上传更新图文到微信服务器
     *
     * @param Attachment $model
     * @param $wechatArticleList
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    protected function isUploadWechatByNews(Attachment $model, $wechatArticleList, $isNewRecord)
    {
        // 如果是微信图文则上传更新到微信服务器
        if ($model->link_type == AttachmentLinkTypeEnum::WECHAT) {
            if (!$isNewRecord) {
                // 更新图文
                foreach ($wechatArticleList as $k => $value) {
                    $res = Yii::$app->wechat->app->draft->update($model['media_id'], $k, $value);
                    Yii::$app->services->base->getWechatError($res);
                }
            } else {
                // 上传图文信息
                $res = Yii::$app->wechat->app->draft->add(['articles' => $wechatArticleList]);
                Yii::$app->services->base->getWechatError($res);
                $model->media_id = $res['media_id'];
                !$model->save() && $this->error($model);

                $getNews = Yii::$app->wechat->app->draft->get($res['media_id']);
                return $getNews['news_item'];
            }
        }

        return [];
    }

    /**
     * 上传封面到微信服务器
     *
     * @param $thumb_url
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    protected function isUploadWechatByImage($thumb_url)
    {
        if (strpos(urldecode($thumb_url), AttachmentLinkTypeEnum::URL) === false) {
            $model = new Attachment();
            $model->local_url = $thumb_url;
            // 上传到微信
            $material = Yii::$app->wechat->app->material->uploadImage(StringHelper::getLocalFilePath($thumb_url));

            $model->media_type = AttachmentTypeEnum::IMAGE;
            $model->media_id = $material['media_id'];
            $model->media_url = $material['url'];
            !$model->save() && $this->error($model);

            return [$model->media_id, $model->media_url];
        }

        return ['', $thumb_url];
    }

    /**
     * 创建资源
     *
     * @param Attachment $model
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function saveCreate(Attachment $model)
    {
        $defaultType = $model->media_type;
        $localFilePath = StringHelper::getLocalFilePath($model->local_url, $defaultType . 's');

        switch ($defaultType) {
            case AttachmentTypeEnum::VIDEO :
                $result = Yii::$app->wechat->app->material->uploadVideo($localFilePath, $model->file_name, $model->description);
                $detail = Yii::$app->wechat->app->material->get($result['media_id']);
                $model->media_url = $detail['down_url'];
                break;
            case AttachmentTypeEnum::IMAGE :
                $result = Yii::$app->wechat->app->material->uploadImage($localFilePath);
                $model->media_url = $result['url'] ?? '';
                $model->file_name = array_slice(explode('/', $model->local_url), -1, 1)[0];
                break;
            case AttachmentTypeEnum::VOICE :
                $result = Yii::$app->wechat->app->material->uploadVoice($localFilePath);
                $model->file_name = array_slice(explode('/', $model->local_url), -1, 1)[0];
                break;
        }

        Yii::$app->services->base->getWechatError($result);
        $model->media_id = $result['media_id'];
        !$model->save() && $this->error($model);

        return $model;
    }

    /**
     * 返回素材列表
     *
     * @param $type
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListByType($type)
    {
        return Attachment::find()
            ->where(['media_type' => $type])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->with('news')
            ->orderBy('id desc')
            ->asArray()
            ->all();
    }

    /**
     * @param $mediaId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findByMediaId($mediaId)
    {
        return Attachment::find()
            ->where(['media_id' => $mediaId])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->asArray()
            ->one();
    }

    /**
     * @param $mediaIds
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListByMediaIds($mediaIds)
    {
        return Attachment::find()
            ->where(['in', 'media_id', $mediaIds])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->select('media_id')
            ->asArray()
            ->all();
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findById($id)
    {
        return Attachment::find()
            ->where(['id' => $id])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->asArray()
            ->one();
    }
}
