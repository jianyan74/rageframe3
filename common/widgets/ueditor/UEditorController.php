<?php

namespace common\widgets\ueditor;

use Exception;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\base\InvalidConfigException;
use common\helpers\ArrayHelper;
use common\helpers\UploadHelper;
use common\enums\AttachmentUploadTypeEnum;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;


/**
 * 百度编辑器
 *
 * Class UEditorController
 * @package common\widgets\ueditor
 * @author jianyan74 <751393839@qq.com>
 */
class UEditorController extends Controller
{
    /**
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * @var array
     */
    public $config = [];

    /**
     * 显示驱动
     *
     * 有Attachment、WechatAttachment、Local
     * @var string
     */
    public $showDrive = 'Attachment';

    /**
     * @var array
     */
    public $actions = [
        'uploadimage' => 'image',
        'uploadscrawl' => 'scrawl',
        'uploadvideo' => 'video',
        'uploadfile' => 'file',
        'listimage' => 'list-image',
        'listfile' => 'list-file',
        'catchimage' => 'catch-image',
        'config' => 'config',
        'listinfo' => 'list-info',
    ];

    /**
     * @var int
     */
    protected $fileStart;

    /**
     * @var int
     */
    protected $fileEnd;

    /**
     * @var int
     */
    protected $fileNum = 0;

    /**
     * @var Local
     */
    protected $filesystem;

    /**
     * 行为控制
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],// 登录
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->config = [
            // server config @see http://fex-team.github.io/ueditor/#server-config
            'scrawlMaxSize' => Yii::$app->params['uploadConfig']['images']['maxSize'],
            'videoMaxSize' => Yii::$app->params['uploadConfig']['videos']['maxSize'],
            'imageMaxSize' => Yii::$app->params['uploadConfig']['images']['maxSize'],
            'fileMaxSize' => Yii::$app->params['uploadConfig']['files']['maxSize'],
            'imageManagerListPath' => Yii::$app->params['uploadConfig']['images']['path'],
            'fileManagerListPath' => Yii::$app->params['uploadConfig']['files']['path'],
            'scrawlFieldName' => 'image',
            'videoFieldName' => 'file',
            'fileFieldName' => 'file',
            'imageFieldName' => 'file',
        ];

        $configPath = Yii::getAlias('@common')."/widgets/ueditor/";
        // 保留UE默认的配置引入方式
        if (file_exists($configPath.'config.json')) {
            $config = Json::decode(preg_replace("/\/\*[\s\S]+?\*\//", '',
                file_get_contents($configPath.'config.json')));
            $this->config = ArrayHelper::merge($config, $this->config);
        }

        // 设置显示驱动
        $showDrive = Yii::$app->request->get('showDrive');
        if (!empty($showDrive) && in_array($showDrive, ['Attachment', 'WechatAttachment', 'Local'])) {
            $this->showDrive = $showDrive;
        }
    }

    /**
     * 后台统一入口
     *
     * @return array|mixed
     */
    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $action = strtolower(Yii::$app->request->get('action', 'config'));
        $actions = $this->actions;
        if (isset($actions[$action])) {
            return $this->run($actions[$action]);
        }

        return $this->result('找不到方法');
    }

    /**
     * 显示配置信息
     */
    public function actionConfig()
    {
        return $this->config;
    }

    /**
     * 上传图片
     *
     * @return array
     */
    public function actionImage()
    {
        try {
            $data = Yii::$app->request->get();
            $upload = Yii::$app->services->extendUpload->saveFile($data, AttachmentUploadTypeEnum::IMAGES);
            $baseInfo = $upload->getInfo();

            return $this->result('SUCCESS', $baseInfo['url']);
        } catch (Exception $e) {
            return $this->result($e->getMessage());
        }
    }

    /**
     * 上传涂鸦
     *
     * @return array
     */
    public function actionScrawl()
    {
        try {
            // 保存扩展名称
            $data = [
                'fileData' => base64_decode(Yii::$app->request->post('image', '')),
                'extend' => Yii::$app->request->post('extend', 'jpg'),
            ];

            $upload = Yii::$app->services->extendUpload->saveFile($data, AttachmentUploadTypeEnum::IMAGES, 'base64');
            $baseInfo = $upload->getInfo();

            return $this->result('SUCCESS', $baseInfo['url']);
        } catch (Exception $e) {
            return $this->result($e->getMessage());
        }
    }

    /**
     * 上传视频
     *
     * @return array
     */
    public function actionVideo()
    {
        try {
            $data = Yii::$app->request->get();
            $upload = Yii::$app->services->extendUpload->saveFile($data, AttachmentUploadTypeEnum::VIDEOS);
            $baseInfo = $upload->getInfo();
            $url = $baseInfo['url'];
            if ($upload->poster == true) {
                $newUpload = UploadHelper::videoPoster($upload);
                $baseInfo = !empty($newUpload) ? $newUpload->getInfo() : [];
                $posterUrl = $baseInfo['url'] ?? '';
            } else {
                $posterUrl = '';
            }

            return [
                'state' => 'SUCCESS',
                'url' => $url,
                'posterUrl' => $posterUrl,
            ];
        } catch (Exception $e) {
            return $this->result($e->getMessage());
        }
    }

    /**
     * 上传文件
     */
    public function actionFile()
    {
        try {
            $data = Yii::$app->request->get();
            $upload = Yii::$app->services->extendUpload->saveFile($data, AttachmentUploadTypeEnum::FILES);
            $baseInfo = $upload->getInfo();

            return $this->result('SUCCESS', $baseInfo['url']);
        } catch (Exception $e) {
            return $this->result($e->getMessage());
        }
    }

    /**
     * 获取远程图片
     *
     * @return array
     * @throws Exception
     */
    public function actionCatchImage()
    {
        /* 上传配置 */
        $source = Yii::$app->request->post('source', []);
        $data = Yii::$app->request->get();

        foreach ($source as $imgUrl) {
            try {
                $data['fileData'] = $imgUrl;
                $upload = Yii::$app->services->extendUpload->saveFile($data, AttachmentUploadTypeEnum::IMAGES, 'url');
                if ($file = Yii::$app->services->attachment->findByMd5($upload->md5)) {
                    $url = $file['url'];
                } else {
                    $baseInfo = $upload->getInfo();
                    $url = $baseInfo['url'];
                }

                $list[] = [
                    'state' => 'SUCCESS',
                    'url' => $url,
                    'source' => $imgUrl,
                ];
            } catch (Exception $e) {
                $list[] = [
                    'state' => $e->getMessage(),
                    'url' => '',
                    'source' => $imgUrl,
                ];
            }
        }

        /* 返回抓取数据 */

        return [
            'state' => count($list) ? 'SUCCESS' : 'ERROR',
            'list' => $list,
        ];
    }

    /**
     * 文件列表
     *
     * @return array
     */
    public function actionListFile()
    {
        $prefix = Yii::$app->params['uploadConfig']['files']['fullPath'] == true ? Yii::$app->request->hostInfo : '';
        $action = 'get'.$this->showDrive;

        return $this->$action(
            $this->config['fileManagerListSize'],
            $this->config['fileManagerListPath'],
            $prefix
        );
    }

    /**
     * 图片列表
     *
     * @return array
     */
    public function actionListImage()
    {
        $prefix = Yii::$app->params['uploadConfig']['images']['fullPath'] == true ? Yii::$app->request->hostInfo : '';
        $action = 'get'.$this->showDrive;

        return $this->$action(
            $this->config['imageManagerListSize'],
            $this->config['imageManagerListPath'],
            $prefix
        );
    }

    /**
     * 获取数据库资源文件列表
     *
     * @param $size
     * @param $path
     * @return array
     */
    public function getAttachment($size, $path)
    {
        $start = Yii::$app->request->get('start');
        $uploadType = $path == $this->config['imageManagerListPath'] ? AttachmentUploadTypeEnum::IMAGES : AttachmentUploadTypeEnum::FILES;
        list($files, $total) = Yii::$app->services->attachment->baiduListPage($uploadType, $start, $size);

        return [
            'state' => 'SUCCESS',
            'list' => $files,
            'start' => $start,
            'total' => $total,
        ];
    }

    /**
     * 文件和图片管理action使用
     *
     * @param $allowFiles
     * @param $listSize
     * @param $path
     * @return array
     */
    protected function getLocal($listSize, $path, $prefix)
    {
        /* 获取参数 */
        $size = Yii::$app->request->get('size', $listSize);
        $this->fileStart = Yii::$app->request->get('start', 0);
        $this->fileEnd = $this->fileStart + $size;

        $files = $this->getLocalFiles($path, $prefix);

        return [
            'state' => 'SUCCESS',
            'list' => $files,
            'start' => $this->fileStart,
            'total' => $this->fileNum,
        ];
    }

    /**
     * @param string $path 文件路径
     * @param string $allowFiles 文件后缀
     * @param array $files 文件列表
     * @param string $prefix 前缀
     * @return array
     */
    public function getLocalFiles($path, $prefix, &$files = [])
    {
        if (!$this->filesystem) {
            $adapter = new Local(Yii::getAlias('@attachment'));
            $this->filesystem = new Filesystem($adapter);
        }

        $listFiles = $this->filesystem->listContents($path);
        foreach ($listFiles as $key => $listFile) {
            if ($listFile['type'] == 'dir') {
                $this->getLocalFiles($listFile['path'], $prefix, $files);
            } else {
                // 获取选中列表
                if ($this->fileNum >= $this->fileStart && $this->fileNum < $this->fileEnd) {
                    $url = $prefix.Yii::getAlias('@attachurl').'/'.$listFile['path'];
                    $files[] = [
                        'url' => $url,
                        'mtime' => $listFile['timestamp'],
                    ];
                }

                $this->fileNum++;
            }

            unset($listFiles[$key]);
        }

        return $files;
    }

    /**
     * 获取当前上传成功文件的各项信息
     * @return array
     */
    protected function result($state = 'ERROR', $url = '')
    {
        return [
            "state" => $state,
            "url" => $url,
        ];
    }
}
