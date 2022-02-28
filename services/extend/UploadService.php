<?php

namespace services\extend;

use Yii;
use common\helpers\Url;
use yii\helpers\Json;
use yii\web\UnprocessableEntityHttpException;
use common\enums\StatusEnum;
use common\forms\UploadForm;
use common\helpers\UploadHelper;
use common\components\Service;
use common\helpers\ArrayHelper;
use common\enums\AttachmentUploadTypeEnum;
use common\enums\AttachmentDriveEnum;

/**
 * Class UploadService
 * @package services\extend
 * @author jianyan74 <751393839@qq.com>
 */
class UploadService extends Service
{
    /**
     * @var UploadForm
     */
    protected $form;

    /**
     * 下载图片
     *
     * @param string $imageUrl
     * @param int $cycleIndex
     * @return array|UploadForm
     * @throws UnprocessableEntityHttpException
     */
    public function downloadByUrl(string $imageUrl, $cycleIndex = 0)
    {
        if (empty($imageUrl)) {
            return [];
        }

        try {
            // 下载图片
            $uploadForm = $this->saveFile([
                'writeTable' => StatusEnum::DISABLED,
                'fileData' => $imageUrl
            ], AttachmentUploadTypeEnum::IMAGES, 'url');

            $info = $uploadForm->getInfo();

            return $info;
        } catch (\Exception $e) {
            $cycleIndex++;
            if ($cycleIndex <= 3) {
                return $this->downloadByUrl($imageUrl, $cycleIndex);
            } else {
                throw new UnprocessableEntityHttpException('获取图片失败');
            }
        }
    }

    /**
     * 上传图片
     *
     * @param array $data
     * @param string $uploadType
     * @param string $fileSource
     * @return UploadForm
     * @throws UnprocessableEntityHttpException
     */
    public function saveFile(array $data, string $uploadType, string $fileSource = 'file')
    {
        $form = new UploadForm();
        $form->attributes = $data;
        $form->upload_type = $uploadType;
        $form->fileSource = $fileSource;
        $form->superAddition = true;
        $form->driveConfig = ArrayHelper::merge(Yii::$app->params['uploadConfig'][$uploadType], $data);
        empty($form->drive) && $form->drive = Yii::$app->services->config->backendConfig('storage_default');
        $form->pathInit();
        $form->fileSystemInit();
        // 判断是否切片上传
        if ($form->chunks > 0 && !empty($form->guid)) {
            $form->drive = AttachmentDriveEnum::LOCAL;
            $form->isCut = true;
        }

        if (!$form->validate()) {
            $this->error($form);
        }

        return UploadHelper::save($form);
    }

    /**
     * 获取阿里云js直传
     *
     * @param $maxSize
     * @param string dir 用户上传文件时指定的前缀
     * @param int $expire 设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
     * @param string $callbackUrl 为上传回调服务器的URL，请将下面的IP和Port配置为您自己的真实URL信息
     * @return array
     * @throws \Exception
     */
    public function ossConfig(
        $maxSize,
        $path = '',
        $expire = 30,
        $type = AttachmentUploadTypeEnum::FILES,
        $callbackUrl = ''
    ) {
        $config = Yii::$app->services->config->configAll();

        $id = $config['storage_aliyun_accesskeyid'];
        $key = $config['storage_aliyun_accesskeysecret'];
        $bucket = $config['storage_aliyun_bucket'];
        $endpoint = $config['storage_aliyun_endpoint'];
        $host = "https://$bucket.$endpoint";
        // CNAME别名
        if (!empty($config['storage_aliyun_user_url'])) {
            $host = $config['storage_aliyun_transport_protocols'] . "://" . $config['storage_aliyun_user_url'];
        }

        $inAddon = Yii::$app->params['inAddon'];
        Yii::$app->params['inAddon'] = false;
        !$callbackUrl && $callbackUrl = Url::toApi(['v1/common/storage/oss'], true);
        Yii::$app->params['inAddon'] = $inAddon;

        $callback_param = [
            'callbackUrl' => $callbackUrl,
            'callbackBody' => 'filename=${object}&size=${size}&mimeType=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}&format=${imageInfo.format}&md5=${x:md5}&merchant_id=${x:merchant_id}&type=${x:type}&host=${x:host}&ip=${x:ip}',
            'callbackBodyType' => "application/x-www-form-urlencoded"
        ];

        $base64_callback_body = base64_encode(Json::encode($callback_param));
        $expiration = $this->expiration(time() + $expire);
        // 最大文件大小
        $conditions[] = ['content-length-range', 0, $maxSize];

        // 表示用户上传的数据，必须是以$dir开始，不然上传会失败，这一步不是必须项，只是为了安全起见，防止用户通过policy上传到别人的目录。
        // $conditions[] = ['starts-with','$filename', $dir];

        $arr = [
            'expiration' => $expiration,
            'conditions' => $conditions
        ];

        $policy = Json::encode($arr);
        $base64_policy = base64_encode($policy);
        $signature = base64_encode(hash_hmac('sha1', $base64_policy, $key, true));

        return [
            'Filename' => '${filename}',
            'key' => $path . '${filename}',
            'OSSAccessKeyId' => $id,
            'success_action_status' => '201',
            'host' => $host,
            'policy' => $base64_policy,
            'signature' => $signature,
            'callback' => $base64_callback_body,
            'x:merchant_id' => Yii::$app->services->merchant->getNotNullId(),
            'x:ip' => Yii::$app->services->base->getUserIp(),
            'x:type' => $type,
            'x:host' => $host,
        ];
    }

    /**
     * 截止日期
     *
     * @param $time
     * @return string
     * @throws \Exception
     */
    protected function expiration($time)
    {
        $dtStr = date("c", $time);
        $datatime = new \DateTime($dtStr);
        $expiration = $datatime->format(\DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);

        return $expiration . "Z";
    }
}
