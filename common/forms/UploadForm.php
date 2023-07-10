<?php

namespace common\forms;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\UploadedFile;
use common\helpers\RegularHelper;
use common\helpers\ArrayHelper;
use common\enums\AttachmentDriveEnum;
use common\enums\AttachmentUploadTypeEnum;
use common\helpers\StringHelper;
use common\helpers\UploadHelper;
use linslin\yii2\curl\Curl;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\AbstractAdapter;
use Overtrue\Flysystem\Cos\CosAdapter;
use Overtrue\Flysystem\Qiniu\QiniuAdapter;
use Xxtime\Flysystem\Aliyun\OssAdapter;
use Overtrue\Flysystem\Qiniu\Plugins\FileUrl;

/**
 * Class UploadForm
 * @package common\forms
 */
class UploadForm extends \common\models\common\Attachment
{
    public $thumb;
    public $chunks;
    public $chunk = 1;
    public $guid;
    public $poster;
    public $isCut = false;
    public $merge = false;
    public $writeTable = true;
    public $superAddition = false;

    /**
     * @var AbstractAdapter
     */
    public $uploadDrive;

    /**
     * @var \League\Flysystem\Filesystem
     */
    public $fileSystem;

    /**
     * 文件来源
     *
     * @var string
     */
    public $fileSource = 'file';

    /**
     * 上传的文件名
     *
     * @var string
     */
    public $fileName = 'file';

    /**
     * 文件内容
     *
     * @var string
     */
    public $fileData;

    /**
     * 驱动配置
     *
     * @var array
     */
    public $driveConfig = [];

    /**
     * 初始化路径
     *
     * @var array
     */
    public $paths = [];

    /**
     * 文件写入的相对路径
     *
     * @var
     */
    public $fileRelativePath;

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge([
            [['fileName', 'fileSource'], 'required'],
            ['fileSource', 'in', 'range' => ['url', 'file', 'base64']],
            [['fileSource'], 'verifyFileSource'],
            [['guid', 'fileData'], 'string'],
            [['thumb', 'chunks', 'chunk', 'image', 'compress', 'merge', 'writeTable'], 'safe'],
        ], parent::rules());
    }

    /**
     * @return array|string[]
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'fileSource' => '文件来源',
            'fileData' => '文件内容',
        ]);
    }

    /**
     * 验证来源
     *
     * @param $attribute
     */
    public function verifyFileSource($attribute)
    {
        switch ($this->fileSource) {
            case 'url' :
                $this->verifyUrl();
                break;
            case 'base64' :
                $this->size = strlen($this->fileData);
                empty($this->extension) && $this->extension = 'jpg';
                break;
            case 'file' :
                $this->verifyFile();
                break;
        }

        $this->fileRelativePath = $this->paths['relativePath'] . $this->name . '.' . $this->extension;
        $this->verify();
    }

    /**
     * 验证文件
     *
     * @throws NotFoundHttpException
     */
    protected function verifyFile()
    {
        $file = UploadedFile::getInstanceByName($this->fileName);
        if (!$file) {
            throw new NotFoundHttpException('找不到上传文件');
        }

        if ($file->getHasError()) {
            throw new NotFoundHttpException('上传失败，请检查文件');
        }

        $this->extension = $file->getExtension();
        $this->size = $file->size;
        empty($this->name) && $this->name = $file->getBaseName();
    }

    /**
     * 验证 Url
     *
     * @throws NotFoundHttpException
     */
    public function verifyUrl()
    {
        $imgUrl = str_replace("&amp;", "&", htmlspecialchars($this->fileData));
        // http开头验证
        if (strpos($imgUrl, "http") !== 0) {
            throw new NotFoundHttpException('不是一个http地址');
        }

        preg_match('/(^https?:\/\/[^:\/]+)/', $imgUrl, $matches);
        $host_with_protocol = count($matches) > 1 ? $matches[1] : '';

        // 判断是否是合法 url
        if (!filter_var($host_with_protocol, FILTER_VALIDATE_URL)) {
            throw new NotFoundHttpException('Url不合法');
        }

        preg_match('/^https?:\/\/(.+)/', $host_with_protocol, $matches);
        $host_without_protocol = count($matches) > 1 ? $matches[1] : '';

        // 此时提取出来的可能是 IP 也有可能是域名，先获取 IP
        $ip = gethostbyname($host_without_protocol);

        // 获取请求头并检测死链
        $heads = get_headers($imgUrl, 1);
        if (!(stristr($heads[0], "200") && stristr($heads[0], "OK"))) {
            throw new NotFoundHttpException('文件获取失败');
        }

        // Content-Type验证)
        if (!isset($heads['Content-Type']) || !stristr($heads['Content-Type'], "image")) {
            throw new NotFoundHttpException('格式验证失败');
        }

        $extend = StringHelper::clipping($imgUrl, '.', 1);
        if (!in_array($extend, Yii::$app->params['uploadConfig']['images']['extensions'])) {
            $extend = 'jpg';
        }

        $img = (new Curl())->get($imgUrl);
        $this->extension = $extend;
        $this->size = strlen($img);
        $this->md5 = md5($img);

        $this->fileData = $img;
    }

    /**
     * 验证文件大小及类型
     *
     * @throws NotFoundHttpException
     */
    protected function verify()
    {
        if ($this->size > $this->driveConfig['maxSize']) {
            throw new NotFoundHttpException('文件大小超出网站限制');
        }

        if (!empty($this->driveConfig['extensions']) && !in_array($this->extension, $this->driveConfig['extensions'])) {
            throw new NotFoundHttpException('文件类型不允许');
        }

        // 存储本地进行安全校验
        if (
            $this->drive == AttachmentDriveEnum::LOCAL &&
            $this->upload_type == AttachmentUploadTypeEnum::FILES &&
            in_array($this->extension, $this->driveConfig['blacklist'])) {
            throw new NotFoundHttpException('上传的文件类型不允许');
        }
    }

    /**
     * 获取生成路径信息
     *
     * @return array
     */
    public function pathInit()
    {
        if (!empty($this->paths)) {
            return $this->paths;
        }

        $config = $this->driveConfig;
        // 保留原名称
        $config['originalName'] == false && $this->name = $config['prefix'] . time() . '_' . StringHelper::random(8);

        // 文件路径
        $filePath = $config['path'] . date($config['subName'], time()) . "/";
        // 缩略图
        $thumbPath = Yii::$app->params['uploadConfig']['thumb']['path'] . date($config['subName'], time()) . "/";

        empty($config['guid']) && $config['guid'] = StringHelper::random(8);
        $tmpPath = 'tmp/' . date($config['subName'], time()) . "/" . $config['guid'] . '/';
        $this->paths = [
            'relativePath' => $filePath, // 相对路径
            'thumbRelativePath' => $thumbPath, // 缩略图相对路径
            'tmpRelativePath' => $tmpPath, // 临时相对路径
        ];

        return $this->paths;
    }

    /**
     * 初始化上传类
     */
    public function fileSystemInit()
    {
        $drive = $this->drive;
        $config = Yii::$app->services->config->configAll();

        switch ($drive) {
            // 阿里云
            case AttachmentDriveEnum::OSS :
                $this->uploadDrive = new OssAdapter([
                    'accessId' => $config['storage_aliyun_accesskeyid'],
                    'accessSecret' => $config['storage_aliyun_accesskeysecret'],
                    'bucket' => $config['storage_aliyun_bucket'],
                    'endpoint' => $config['storage_aliyun_is_internal'] == true ? $config['storage_aliyun_endpoint_internal'] : $config['storage_aliyun_endpoint'],
                    // 'timeout' => 3600,
                    // 'connectTimeout' => 10,
                    // 'isCName' => false,
                    // 'token' => '',
                ]);
                break;
            // 腾讯云
            case AttachmentDriveEnum::COS :
                $this->uploadDrive = new CosAdapter([
                    'region' => $config['storage_cos_region'], // 'ap-guangzhou'
                    'credentials' => [
                        'appId' => $config['storage_cos_appid'], // 域名中数字部分
                        'secretId' => $config['storage_cos_accesskey'],
                        'secretKey' => $config['storage_cos_secrectkey'],
                    ],
                    'bucket' => $config['storage_cos_bucket'],
                    'timeout' => 60,
                    'connect_timeout' => 60,
                    'cdn' => $config['storage_cos_cdn'],
                    'scheme' => 'https',
                    'read_from_cdn' => !empty($config['read_from_cdn']),
                ]);
                break;
            // 七牛
            case AttachmentDriveEnum::QINIU :
                $this->uploadDrive = new QiniuAdapter(
                    $config['storage_qiniu_accesskey'],
                    $config['storage_qiniu_secrectkey'],
                    $config['storage_qiniu_bucket'],
                    $config['storage_qiniu_domain']
                );
                break;
            // 本地
            default :
                // 判断是否追加
                if ($this->superAddition) {
                    $this->uploadDrive = new Local(Yii::getAlias('@attachment'), FILE_APPEND);
                } else {
                    $this->uploadDrive = new Local(Yii::getAlias('@attachment'));
                }
                break;
        }

        $this->fileSystem = new Filesystem($this->uploadDrive);
    }

    /**
     * @return array
     * @throws UnprocessableEntityHttpException
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \yii\base\InvalidConfigException
     */
    public function getInfo()
    {
        $this->path = $this->fileRelativePath;
        $this->isCut == false && $this->specific_type = $this->fileSystem->getMimetype($this->path);
        $this->url = $this->getUrl();
        $this->year = date('Y');
        $this->month = date('m');
        $this->day = date('d');
        $this->member_id = Yii::$app->services->member->getAutoId();
        $this->ip = Yii::$app->services->base->getUserIp();
        $this->req_id = Yii::$app->params['uuid'];
        $this->format_size = Yii::$app->formatter->asShortSize($this->size, 2);

        // 如果是图片且内容是文字类型
        if (
            in_array($this->extension, Yii::$app->params['uploadConfig']['images']['extensions']) &&
            in_array($this->specific_type, ['text/plain'])
        ) {
            $this->fileSystem->delete($this->path);
            Yii::$app->services->actionLog->create('alarm', '用户试图上传病毒文件');
            throw new UnprocessableEntityHttpException('警告这是非法文件');
        }

        if ($this->isCut == false && $this->writeTable && Yii::$app->params['fileWriteTable']) {
            $this->superAddition == true ? $this->save(false) : $this->save();
        }

        return ArrayHelper::merge(ArrayHelper::toArray($this), [
            'merge' => $this->merge,
            'guid' => $this->guid,
            'chunk' => $this->chunk,
            'chunks' => $this->chunks,
            'upload_type' => UploadHelper::formattingFileType($this->specific_type, $this->extension, $this->upload_type)
        ]);
    }

    /**
     * @return mixed|string
     */
    protected function getUrl()
    {
        $config = Yii::$app->services->config->configAll();
        switch ($this->drive) {
            // 阿里云
            case AttachmentDriveEnum::OSS :
                $url = $config['storage_aliyun_user_url'];
                if (!empty($url)) {
                    return $config['storage_aliyun_transport_protocols'] . '://' . $url . '/' . $this->path;
                }

                $raw = $this->uploadDrive->supports->getFlashData();

                return $raw['info']['url'];
            // 腾讯云
            case AttachmentDriveEnum::COS :
                if (empty($config['read_from_cdn'])) {
                    $bucket = $config['storage_cos_bucket'] ?? '';
                    $appid = $config['storage_cos_appid'] ?? '';
                    $region = $config['storage_cos_region'] ?? '';
                    return 'https://' . $bucket . '-' . $appid . '.cos.' . $region . '.myqcloud.com/' . $this->path;
                }

                return $config['storage_cos_cdn'] . $this->path;
            // 七牛
            case AttachmentDriveEnum::QINIU :
                $this->fileSystem->addPlugin(new FileUrl());

                return $this->fileSystem->getUrl($this->path);
            // 本地
            default :
                $hostInfo = Yii::$app->request->hostInfo ?? '';
                $url = Yii::getAlias('@attachurl') . '/' . $this->path;
                if ($this->driveConfig['fullPath'] == true && !RegularHelper::verify('url', $url)) {
                    return $hostInfo . $url;
                }

                return $url;
        }
    }
}
