<?php

namespace services\common;

use Yii;
use yii\helpers\Json;
use linslin\yii2\curl\Curl;
use common\helpers\ResultHelper;
use common\helpers\FileHelper;
use common\helpers\ZipHelper;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use Exception;

/**
 * Class RageFrameService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class RageFrameService
{
    /**
     * 更新地址
     */
    const URL = 'http://authority.rageframe.com/api';

    /**
     * @param $addonName
     * @return array|mixed
     * @throws Exception
     */
    public function update($addonName = 'Authority')
    {
        $data = $this->getVersion($addonName);
        if (empty($data)) {
            throw new UnprocessableEntityHttpException('当前已是最新版本');
        }

        // 更新包信息
        $fileUrl = $data['download_url']; //更新包的下载地址
        $fileName = basename($fileUrl); //更新包文件名称

        // 检查和创建文件夹
        $dir = Yii::getAlias('@runtime').'/version-update/'.$addonName.'/';
        FileHelper::mkdirs($dir);

        // 下载更新包到本地并赋值文件路径变量
        $path = file_exists($dir.$fileName) ? $dir.$fileName : $this->downloadFile($fileUrl, $dir, $fileName);
        // 如果下载没成功就返回报错
        if (!file_exists($dir.$fileName)) {
            throw new UnprocessableEntityHttpException('文件下载失败！');
        }

        // 解压文件夹
        $toPath = Yii::getAlias('@root');
        // 插件
        $addonName != 'Authority' && $toPath .= '/addons/'.$addonName;

        try {
            ZipHelper::unZip($path, $toPath, true);
        } catch (Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        // 更新数据库
        Yii::$app->services->addons->upgradeSql($addonName);

        return $data;
    }

    /**
     * 批量查询插件最新版本
     *
     * @param $addons
     * @return mixed|array
     * @throws UnprocessableEntityHttpException
     */
    public function queryNewestByNames($addons)
    {
        return $this->httpPost('/authority/v1/accredit/batch-inspect', [
            'addons' => $addons,
        ]);
    }

    /**
     * 查询单个最新版本
     *
     * @param $addonName
     * @return array|mixed|void
     * @throws Exception
     */
    public function queryNewest($addonName = 'Authority')
    {
        return $this->getVersion($addonName);
    }

    /**
     * 获取版本
     *
     * @param $addonName
     * @return mixed
     * @throws UnprocessableEntityHttpException
     */
    protected function getVersion($addonName)
    {
        $addon = Yii::$app->services->addons->findByName($addonName);
        if (empty($addon)) {
            throw new UnprocessableEntityHttpException('找不到插件['.$addonName.']更新失败');
        }

        return $this->httpPost('/authority/v1/accredit/inspect', [
            'version' => $addon->version,
            'name' => $addon->name,
        ]);
    }

    /**
     * @param $uri
     * @param array $params
     * @return mixed
     * @throws UnprocessableEntityHttpException
     */
    protected function httpPost($uri, array $params)
    {
        $curl = new Curl();
        $result = $curl->setHeaders([
            'secret-key' => Yii::$app->params['secret_key'],
            'domain-name' => Yii::$app->request->hostInfo,
            'ip' => Yii::$app->request->userIP,
            'timestamp' => time(),
        ])->setPostParams($params)->post(self::URL.$uri);

        try {
            // 回调解析失败
            $result = Json::decode($result);
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException('已经是最新版本');
        }

        if ($result['code'] != 200) {
            throw new UnprocessableEntityHttpException($result['message']);
        }

        return $result['data'];
    }

    /**
     * 文件下载方法
     *
     * @param string $url 文件下载地址
     * @param string $dir 存储的文件夹
     * @param string $fileName 文件名字
     * @return array|false|mixed|string
     */
    protected function downloadFile($url, $dir, $fileName = '')
    {
        if (empty($url)) {
            return false;
        }

        $ext = strrchr($url, '.');
        $dir = realpath($dir);
        //目录+文件
        $fileName = (empty($fileName) ? '/'.time().''.$ext : '/'.$fileName);
        $fileName = $dir.$fileName;
        //开始捕捉
        ob_start();

        try {
            readfile($url);
        } catch (Exception $e) {
            throw new NotFoundHttpException('文件下载失败, ' . $e->getMessage());
        }

        $file = ob_get_contents();
        ob_end_clean();
        $size = strlen($file);
        $fp2 = fopen($fileName, "a");
        fwrite($fp2, $file);
        fclose($fp2);

        return $fileName;
    }
}
