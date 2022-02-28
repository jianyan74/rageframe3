<?php

namespace common\traits;

use Yii;
use yii\web\Response;
use common\forms\UploadForm;
use common\helpers\ResultHelper;
use common\helpers\UploadHelper;
use common\enums\AttachmentUploadTypeEnum;

/**
 * Trait FileAction
 * @package common\traits
 * @author jianyan74 <751393839@qq.com>
 */
trait FileAction
{
    /**
     * 根据md5获取文件
     *
     * @return array
     */
    public function actionVerifyMd5()
    {
        $md5 = Yii::$app->request->post('md5');
        $drive = Yii::$app->request->post('drive');
        $cate_id = Yii::$app->request->post('cate_id');
        $upload_type = Yii::$app->request->post('upload_type');
        if ($file = Yii::$app->services->attachment->findByMd5($md5, $drive, $cate_id, $upload_type)) {
            $file['size'] = Yii::$app->formatter->asShortSize($file['size'], 2);
            $file['upload_type'] = UploadHelper::formattingFileType($file['specific_type'], $file['extension'], $file['upload_type']);

            return ResultHelper::json(200, '获取成功', $file);
        }

        return ResultHelper::json(404, '找不到文件');
    }

    /**
     * base64编码的上传
     *
     * @return array
     */
    public function actionBase64()
    {
        try {
            // 保存扩展名称
            $extend = Yii::$app->request->post('extend', 'jpg');
            !in_array($extend, Yii::$app->params['uploadConfig']['images']['extensions']) && $extend = 'jpg';

            $data = [
                'fileData' => base64_decode(Yii::$app->request->post('image', '')),
                'extend' => $extend
            ];

            $upload = Yii::$app->services->extendUpload->saveFile($data, AttachmentUploadTypeEnum::IMAGES, 'base64');

            return ResultHelper::json(200, '上传成功', $upload->getInfo());
        } catch (\Exception $e) {
            return ResultHelper::json(404, $e->getMessage());
        }
    }

    /**
     * Markdown 图片上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionImagesMarkdown()
    {
        try {
            $data = Yii::$app->request->get();
            $data['fileName'] = 'editormd-image-file';
            $upload = Yii::$app->services->extendUpload->saveFile($data, AttachmentUploadTypeEnum::IMAGES);
            $info = $upload->getInfo();

            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'success' => 1,
                'url' => $info['url'],
            ];
        } catch (\Exception $e) {
            return ResultHelper::json(404, $e->getMessage());
        }
    }

    /**
     * 图片上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionImages()
    {
        try {
            $data = Yii::$app->request->post();
            $upload = Yii::$app->services->extendUpload->saveFile($data, AttachmentUploadTypeEnum::IMAGES);

            return ResultHelper::json(200, '上传成功', $upload->getInfo());
        } catch (\Exception $e) {
            return ResultHelper::json(422, $e->getMessage());
        }
    }

    /**
     * 文件上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionFiles()
    {
        try {
            $data = Yii::$app->request->post();
            $upload = Yii::$app->services->extendUpload->saveFile($data, AttachmentUploadTypeEnum::FILES);

            return ResultHelper::json(200, '上传成功', $upload->getInfo());
        } catch (\Exception $e) {
            return ResultHelper::json(404, $e->getMessage());
        }
    }

    /**
     * 视频上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionVideos()
    {
        try {
            $data = Yii::$app->request->post();
            $upload = Yii::$app->services->extendUpload->saveFile($data, AttachmentUploadTypeEnum::VIDEOS);

            return ResultHelper::json(200, '上传成功', $upload->getInfo());
        } catch (\Exception $e) {
            return ResultHelper::json(404, $e->getMessage());
        }
    }

    /**
     * 语音上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionVoices()
    {
        try {
            $data = Yii::$app->request->post();
            $upload = Yii::$app->services->extendUpload->saveFile($data, AttachmentUploadTypeEnum::VOICES);

            return ResultHelper::json(200, '上传成功', $upload->getInfo());
        } catch (\Exception $e) {
            return ResultHelper::json(404, $e->getMessage());
        }
    }

    /**
     * 合并
     *
     * @return array|mixed
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \Exception
     */
    public function actionMerge()
    {
        $guid = Yii::$app->request->post('guid');
        $upload = Yii::$app->cache->get(UploadHelper::PREFIX_MERGE_CACHE . $guid);
        if (empty($upload)) {
            return ResultHelper::json(404, '找不到文件信息, 合并文件失败');
        }

        /** @var UploadForm $upload */
        $upload->superAddition = true;
        $upload->fileSystemInit();
        UploadHelper::merge($upload);

        Yii::$app->cache->delete('upload-file-guid:' . $guid);

        return ResultHelper::json(200, '上传成功', $upload->getInfo());
    }

    /**
     * oss直传配置
     *
     * @return array
     * @throws \Exception
     */
    public function actionOssAccredit()
    {
        // 上传类型
        $type = Yii::$app->request->get('type');
        $typeConfig = Yii::$app->params['uploadConfig'][$type];

        $path = $typeConfig['path'] . date($typeConfig['subName'], time()) . "/";
        $oss = Yii::$app->services->extendUpload->ossConfig($typeConfig['maxSize'], $path, 60 * 60 * 2, $type);

        return $oss;
    }

    /**
     * 权限验证
     *
     * @param string $action 当前的方法
     * @param null $model 当前的模型类
     * @param array $params $_GET变量
     * @throws \yii\web\BadRequestHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // 方法名称
        if (in_array($action, ['index', 'view', 'update', 'create', 'delete'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}
