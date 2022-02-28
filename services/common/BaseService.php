<?php

namespace services\common;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class BaseService
 * @package services\common
 */
class BaseService
{
    /**
     * 当前版本号
     *
     * @return mixed|string
     * @throws NotFoundHttpException
     */
    public function version()
    {
        $update = Yii::$app->services->addons->findAuthority();

        return $update->version ?? '未授权';
    }

    /**
     * @return int
     * @throws \yii\db\Exception
     */
    public function getDefaultDbSize()
    {
        $models = Yii::$app->db->createCommand('SHOW TABLE STATUS')->queryAll();
        $models = array_map('array_change_key_case', $models);
        // 数据库大小
        $mysqlSize = 0;
        foreach ($models as $model) {
            $mysqlSize += $model['data_length'];
        }

        return $mysqlSize;
    }

    /**
     * @return false|int|string
     */
    public function getUserIp()
    {
        return Yii::$app->request->userIP ?? '0.0.0.0';
    }

    /**
     * 打印
     *
     * @param mixed ...$array
     */
    public function p(...$array)
    {
        echo "<pre>";

        if (count($array) == 1) {
            print_r($array[0]);
        } else {
            print_r($array);
        }

        echo '</pre>';
    }

    /**
     * 解析系统报错
     *
     * @param \Exception $e
     * @return array
     */
    public function getErrorInfo(\Exception $e)
    {
        return [
            'errorMessage' => $e->getMessage(),
            'type' => get_class($e),
            'file' => method_exists($e, 'getFile') ? $e->getFile() : '',
            'line' => $e->getLine(),
            'stack-trace' => explode("\n", $e->getTraceAsString()),
        ];
    }

    /**
     * 解析微信是否报错
     *
     * @param array $message 微信回调数据
     * @param bool $direct 是否直接报错
     * @return bool
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getWechatError($message, $direct = true)
    {
        if (isset($message['errcode']) && $message['errcode'] != 0) {
            // token过期 强制重新从微信服务器获取 token.
            if ($message['errcode'] == 40001) {
                Yii::$app->wechat->app->access_token->getToken(true);
            }

            if ($direct) {
                throw new UnprocessableEntityHttpException($message['errmsg']);
            }

            return $message['errmsg'];
        }

        return false;
    }

    /**
     * 解析错误
     *
     * @param $fistErrors
     * @return string
     */
    public function analysisErr($firstErrors)
    {
        if (!is_array($firstErrors) || empty($firstErrors)) {
            return false;
        }

        $errors = array_values($firstErrors)[0];

        return $errors ?? '未捕获到错误信息';
    }
}
