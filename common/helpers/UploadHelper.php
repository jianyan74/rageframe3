<?php

namespace common\helpers;

use Yii;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use common\forms\UploadForm;
use common\enums\AttachmentUploadTypeEnum;
use common\enums\AttachmentDriveEnum;

/**
 * 上传辅助类
 *
 * Class UploadHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class UploadHelper
{
    /**
     * 切片合并缓存前缀
     */
    const PREFIX_MERGE_CACHE = 'upload-file-guid:';
    /**
     * @var
     */
    protected $form;

    /**
     * 写入
     *
     * @param bool $data
     * @throws NotFoundHttpException
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public static function save(UploadForm $form)
    {
        // 拦截 如果是切片上传就接管
        if ($form->isCut == true) {
             return self::cut($form);
        }

        // 判断如果文件存在就重命名文件名
        if ($form->fileSystem->has($form->fileRelativePath)) {
            $name = explode('_', $form->name);
            $form->name = $name[0] . '_' . time() . '_' . StringHelper::random(8);
            $form->fileRelativePath = $form->paths['relativePath'] . $form->name . '.' . $form->extension;
        }

        // 判断是否直接写入
        if (empty($form->fileData)) {
            $file = UploadedFile::getInstanceByName($form->fileName);
            if (!$file->getHasError()) {
                $stream = fopen($file->tempName, 'r+');
                $result = $form->fileSystem->writeStream($form->fileRelativePath, $stream);

                if (!$result) {
                    throw new NotFoundHttpException('文件写入失败');
                }

                if (is_resource($stream)) {
                    fclose($stream);
                }
            } else {
                throw new NotFoundHttpException('上传失败，可能文件太大了');
            }
        } else {
            $result = $form->fileSystem->write($form->fileRelativePath, $form->fileData);
            if (!$result) {
                throw new NotFoundHttpException('文件写入失败');
            }
        }

        // 本地的图片才可执行
        if ($form->upload_type == AttachmentUploadTypeEnum::IMAGES && $form->drive == AttachmentDriveEnum::LOCAL) {
            // 图片水印
            self::watermark($form);
            // 图片压缩
            // self::compress($form);
            // 创建缩略图
            // self::thumb($form);

            // 获取图片信息
            if (empty($form->width) && empty($form->height) && $form->fileSystem->has($form->fileRelativePath)) {
                $imgInfo = getimagesize(Yii::getAlias('@attachment') . '/' . $form->fileRelativePath);
                $form->width = $imgInfo[0] ?? 0;
                $form->height = $imgInfo[1] ?? 0;
            }
        }

        return $form;
    }

    /**
     * 获取视频封面图
     *
     * @param UploadForm $form
     * @return UploadForm|false
     * @throws NotFoundHttpException
     * @throws \League\Flysystem\FileExistsException
     */
    public static function videoPoster(UploadForm $form)
    {
        // use `ffmpeg` get first frame as video poster
        // save poster local and upload to cloud, return the cloud url
        $file = UploadedFile::getInstanceByName($form->fileName);
        if ($file->error === UPLOAD_ERR_OK) {
            $form->upload_type = AttachmentUploadTypeEnum::IMAGES;
            $form->name = $form->name . '_poster';
            $form->extension = 'jpg';
            $form->fileRelativePath = $form->paths['relativePath'] . $form->name . '.' . $form->extension;
            $tmpPosterFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $form->name . '.' . $form->extension;
            FfmpegHelper::imageResize($file->tempName, $tmpPosterFilePath, 0);

            if (file_exists($tmpPosterFilePath)) {
                $stream = fopen($tmpPosterFilePath, 'r+');
                $result = $form->fileSystem->writeStream($form->fileRelativePath, $stream);

                if (!$result) {
                    throw new NotFoundHttpException('文件写入失败');
                }
                if (is_resource($stream)) {
                    fclose($stream);
                }

                $imgInfo = getimagesize($tmpPosterFilePath);
                $form->width = $imgInfo[0] ?? 0;
                $form->height = $imgInfo[1] ?? 0;

                unlink($tmpPosterFilePath); // delete tmp file
                return $form;
            }
        }

        return false;
    }

    /**
     * 水印
     *
     * @param $fullPathName
     * @return bool
     */
    protected static function watermark(UploadForm $form)
    {
        if (Yii::$app->services->config->backendConfig('sys_image_watermark_status') != true) {
            return true;
        }

        // 原图路径
        $absolutePath = Yii::getAlias("@attachment/") . $form->fileRelativePath;

        $local = Yii::$app->services->config->backendConfig('sys_image_watermark_location');
        $watermarkImg = StringHelper::getLocalFilePath(Yii::$app->services->config->backendConfig('sys_image_watermark_img'));

        if ($coordinate = DebrisHelper::getWatermarkLocation($absolutePath, $watermarkImg, $local)) {
            // $aliasName = StringHelper::getAliasUrl($fullPathName, 'watermark');
            Image::watermark($absolutePath, $watermarkImg, $coordinate)->save($absolutePath, ['quality' => 100]);
        }

        return true;
    }

    /**
     * 压缩
     *
     * @param $fullPathName
     * @return bool
     */
    protected static function compress(UploadForm $form)
    {
        if ($form->driveConfig['compress'] != true) {
            return true;
        }

        // 原图路径
        $absolutePath = Yii::getAlias("@attachment/") . $form->fileRelativePath;
        $imgInfo = getimagesize($absolutePath);
        $compressibility = $form->driveConfig['compressibility'];
        $tmpMinSize = 0;
        foreach ($compressibility as $key => $item) {
            if ($form->size >= $tmpMinSize && $form->size < $key && $item < 100) {
                // $aliasName = StringHelper::getAliasUrl($fullPathName, 'compress');
                Image::thumbnail($absolutePath, $imgInfo[0], $imgInfo[1])->save($absolutePath, ['quality' => $item]);

                break;
            }

            $tmpMinSize = $key;
        }

        return true;
    }

    /**
     * 缩略图
     *
     * @return bool
     */
    protected static function thumb(UploadForm $form)
    {
        if (empty($form->thumb)) {
            return true;
        }

        // 原图路径
        $absolutePath = Yii::getAlias("@attachment/") . $form->fileRelativePath;
        // 缩略图路径
        $path = Yii::getAlias("@attachment/") . $form->paths['thumbRelativePath'];
        FileHelper::mkdirs($path);
        $thumbPath = $path . $form->name . '.' . $form->extension;

        foreach ($form->thumb as $value) {
            $thumbFullPath = StringHelper::createThumbUrl($thumbPath, $value['width'], $value['height']);
            // 裁剪从坐标0,60 裁剪一张300 x 20 的图片,并保存 不设置坐标则从坐标0，0开始
            // Image::crop($originalPath, $thumbWidth , $thumbHeight, [0, 60])->save($thumbOriginalPath), ['quality' => 100]);
            Image::thumbnail($absolutePath, $value['width'], $value['height'])->save($thumbFullPath);
        }

        return true;
    }

    /**
     * 切片
     *
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public static function cut(UploadForm $form): UploadForm
    {
        // 切片参数
        $chunk = $form->chunk + 1;
        $guid = $form->guid;

        // 临时文件夹路径
        $url = $form->paths['tmpRelativePath'] . $chunk . '.' . $form->extension;
        // 上传
        $file = UploadedFile::getInstanceByName($form->fileName);
        if ($file->error === UPLOAD_ERR_OK) {
            $stream = fopen($file->tempName, 'r+');
            $result = $form->fileSystem->writeStream($url, $stream);
            fclose($stream);
            // 判断如果上传成功就去合并文件

            if (($form->chunks - 1) <= $chunk) {
                // 缓存上传信息等待回调
                $form->uploadDrive = '';
                $form->fileSystem = '';
                Yii::$app->cache->set(self::PREFIX_MERGE_CACHE . $guid, $form, 3600);
            }

            $form->merge = true;
        }

        return $form;
    }

    /**
     * 切片合并
     *
     * @param int $name
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public static function merge(UploadForm $form, $name = 1)
    {
        // 由于合并会附带上一次切片的信息，取消切片判断
        $form->isCut = false;
        $filePath = $form->paths['tmpRelativePath'] . $name . '.' . $form->extension;

        if ($form->fileSystem->has($filePath) && ($content = $form->fileSystem->read($filePath))) {
            if ($form->fileSystem->has($form->fileRelativePath)) {
                $form->fileSystem->update($form->fileRelativePath, $content);
            } else {
                $form->fileSystem->write($form->fileRelativePath, $content);
            }

            unset($content);
            $form->fileSystem->delete($filePath);
            $name += 1;
            self::merge($form, $name);
        } else {
            // 删除文件夹，如果删除失败重新去合并
            $form->fileSystem->deleteDir($form->paths['tmpRelativePath']);
            return $form;
        }
    }

    /**
     * @param $specific_type
     * @param $extension
     * @return string
     */
    public static function formattingFileType($specific_type, $extension, $upload_type)
    {
        if (preg_match("/^image/", $specific_type) && $extension != 'psd') {
            return AttachmentUploadTypeEnum::IMAGES;
        }

        return $upload_type;
    }
}
