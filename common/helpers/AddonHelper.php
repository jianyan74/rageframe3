<?php

namespace common\helpers;

use Yii;
use yii\helpers\Json;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

/**
 * Class AddonHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class AddonHelper
{
    /**
     * 获取配置文件
     *
     * @param $name
     * @return bool|string
     */
    public static function getConfigClass($name)
    {
        if (!class_exists($class = static::getAddonConfig($name))) {
            return false;
        }

        return $class;
    }

    /**
     * 获取插件配置
     *
     * @param $name
     * @return string
     */
    public static function getAddonConfig($name)
    {
        return static::getAddonRoot($name) . "AddonConfig";
    }

    /**
     * 获取插件的命名空间
     *
     * @param $name
     * @return string
     */
    public static function getAddonRoot($name)
    {
        return "addons" . "\\" . $name . "\\";
    }

    /**
     * 获取插件的根目录目录
     *
     * @param $name
     * @return string
     */
    public static function getAddonRootPath($name)
    {
        return Yii::getAlias('@addons') . "/{$name}/";
    }

    /**
     * 验证插件目录是否存在
     *
     * @param $name
     * @return bool
     */
    public static function has($name)
    {
        if (!is_dir(static::getAddonRootPath($name))) {
            return false;
        }

        return true;
    }

    /**
     * @param $name
     * @return string
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public static function getAddonIcon($name)
    {
        $adapter = new Local(Yii::getAlias('@root'));
        $filesystem = new Filesystem($adapter);

        $localIconPath = static::getAddonRoot($name) . 'icon.jpg';
        if ($filesystem->has($localIconPath)) {
            $md5 = md5(Json::encode($filesystem->getMetadata($localIconPath)));
            $newPath = '/assets/tmp/' . $md5 . '.jpg';
            $newLocalIconPath = 'web/backend' . $newPath;

            if (!$filesystem->has($newLocalIconPath)) {
                $filesystem->copy($localIconPath, $newLocalIconPath);
            }

            // 后台独立域名
            if ($backendUrl = Yii::getAlias('@backendUrl')) {
                return $backendUrl . $newPath;
            }

            return '/backend' . $newPath;
        }

        return false;
    }

    /**
     * 获取生成asset的资源文件目录
     *
     * @param string $assets
     * @return string
     */
    public static function filePath($assets = '')
    {
        if (!$assets) {
            $assets = [];
            $assets[] = 'addons';
            $assets[] = Yii::$app->params['addon']['name'];
            $assets[] = Yii::$app->params['realAppId'];
            $assets[] = 'assets';
            $assets[] = 'AppAsset';
            $assets = implode('\\', $assets);
        }

        if (!isset(Yii::$app->view->assetBundles[$assets])) {
            /* @var $assets \yii\web\AssetBundle */
            $assets::register(Yii::$app->view);
        }

        return Yii::$app->view->assetBundles[$assets]->baseUrl . '/';
    }

    /**
     * 获取资源文件
     *
     * @return string
     */
    public static function file($path, $assets = '')
    {
        return self::filePath($assets) . $path;
    }

    /**
     * @param $path
     * @param array $options
     * @param string $assets
     * @return string
     */
    public static function jsFile($path, $options = [], $assets = '')
    {
        return Html::jsFile(self::filePath($assets) . $path, $options);
    }

    /**
     * @param $path
     * @param array $options
     * @param string $assets
     * @return string
     */
    public static function cssFile($path, $options = [], $assets = '')
    {
        return Html::cssFile(self::filePath($assets) . $path, $options);
    }
}
