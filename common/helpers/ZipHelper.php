<?php

namespace common\helpers;

use ZipArchive;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class ZipHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class ZipHelper
{
    /**
     * 将指定文件添加到 zip 中
     *
     * @param string $zipPath zip 文件
     * @param string $filePath 文件路径
     * @param string $fileName 重命名文件名
     * @return void
     */
    public static function addFile($zipPath, $filePath, $fileName = '')
    {
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
            throw new UnprocessableEntityHttpException('打不开指定文件');
        }

        $zip->addFile($filePath, $fileName);
        $zip->close();
    }

    /**
     * 批量添加文件目录到 zip
     *
     * @param string $zipPath
     * @param string $fileDirectory 文件目录
     * @param string $replacePrefix 替换前缀
     * @return void
     * @throws UnprocessableEntityHttpException
     */
    public static function addFileByPath($zipPath, $fileDirectory, $replacePrefix = '')
    {
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
            throw new UnprocessableEntityHttpException('打不开指定文件');
        }

        // 将目录下所有文件添加到 zip 中
        if ($handle = opendir($fileDirectory)) {
            // 添加目录中的所有文件
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && !is_dir($fileDirectory.'/'.$entry)) {
                    if ($replacePrefix) {
                        $zip->addFile($fileDirectory.'/'.$entry, StringHelper::replace($replacePrefix, '', $fileDirectory).'/'.$entry);
                    } else {
                        $zip->addFile($fileDirectory.'/'.$entry);
                    }
                }
            }

            closedir($handle);
        }

        $zip->close();
    }

    /**
     * 解压到指定目录
     *
     * @param $zipPath
     * @param $fileDirectory
     * @return void
     */
    public static function unZip($zipPath, $fileDirectory, $deleteOriginalFile = false)
    {
        $zip = new ZipArchive;
        if ($zip->open($zipPath) !== true) {
            throw new UnprocessableEntityHttpException('打不开指定文件');
        }

        // 解压
        $zip->extractTo($fileDirectory);
        $zip->close();

        // 删除源文件
        if ($deleteOriginalFile) {
            unlink($zipPath);
        }
    }
}
