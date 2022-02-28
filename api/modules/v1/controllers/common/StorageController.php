<?php

namespace api\modules\v1\controllers\common;

use Yii;
use Qiniu\Auth;
use linslin\yii2\curl\Curl;
use common\helpers\ResultHelper;
use common\enums\AttachmentDriveEnum;
use yii\web\Response;
use api\controllers\OnAuthController;

/**
 * Class StorageController
 * @package api\modules\v1\controllers\common
 * @author jianyan74 <751393839@qq.com>
 */
class StorageController extends OnAuthController
{
    /**
     * @var string[]
     */
    public $authOptional = ['oss'];

    /**
     * @var string
     */
    public $modelClass = '';

    /**
     *
     * 建议增加字段 ip,type,host,merchant_id
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     * @throws \Exception
     */
    public function actionOss()
    {
        if ($this->ossSignVerify() === false) {
            return ResultHelper::json(422, '签名校验失败');
        }

        $data = Yii::$app->request->post();
        $baseUrlArr = explode('/', $data['filename']);
        $fileName = end($baseUrlArr);
        $fileName = explode('.', $fileName);
        unset($fileName[count($fileName) - 1]);
        $name = implode('.', $fileName);

        $baseInfo = [
            'drive' => AttachmentDriveEnum::OSS,
            'upload_type' => $data['type'],
            'specific_type' => $data['mimeType'],
            'size' => $data['size'],
            'extension' => $data['format'],
            'name' => $name,
            'width' => $data['width'],
            'height' => $data['height'],
            'url' => urldecode($data['host']) . '/' . $data['filename'],
            'path' => $data['filename'],
            'ip' => $data['ip'] ?? '',
            'md5' => $data['md5'] ?? '',
            'format_size' => Yii::$app->formatter->asShortSize($data['size'], 2),
        ];

        Yii::$app->services->merchant->setId($data['merchant_id'] ?? 0);
        $attachment = Yii::$app->services->attachment->create($baseInfo);

        // 百度编辑器返回
        if (isset($data['is_ueditor']) && $data['is_ueditor'] == 'ueditor') {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                "state" => 'SUCCESS',
                "url" => $attachment['url'],
            ];
        }

        return ResultHelper::json(200, '获取成功', $attachment);
    }

    /**
     * @return array|bool
     * @throws \Exception
     */
    protected function ossSignVerify()
    {
        // 1.获取OSS的签名header和公钥url header
        $authorizationBase64 = Yii::$app->request->headers->get('authorization');
        $pubKeyUrlBase64 = Yii::$app->request->headers->get('x-oss-pub-key-url');
        if (!$authorizationBase64 || !$pubKeyUrlBase64) {
            return false;
        }

        // 2.获取OSS的签名
        $authorization = base64_decode($authorizationBase64);

        // 3.获取公钥
        $pubKeyUrl = base64_decode($pubKeyUrlBase64);
        $curl = new Curl();
        $pubKey = $curl->get($pubKeyUrl);
        if ($pubKey == "") {
            return false;
        }

        // 4.获取回调body
        $body = file_get_contents('php://input');

        // 5.拼接待签名字符串
        $path = $_SERVER['REQUEST_URI'];
        $pos = strpos($path, '?');
        if ($pos === false) {
            $authStr = urldecode($path) . "\n" . $body;
        } else {
            $authStr = urldecode(substr($path, 0, $pos)) . substr($path, $pos, strlen($path) - $pos) . "\n" . $body;
        }

        // 6.验证签名
        $res = openssl_verify($authStr, $authorization, $pubKey, OPENSSL_ALGO_MD5);
        if ($res == 1) {
            return true;
        }

        return false;
    }

    /**
     * 七牛回调
     */
    public function actionQiNiu()
    {
//        $accessKey = getenv('QINIU_ACCESS_KEY');
//        $secretKey = getenv('QINIU_SECRET_KEY');
//        $bucket = getenv('QINIU_TEST_BUCKET');
//        $auth = new Auth($accessKey, $secretKey);
//        //获取回调的body信息
//        $callbackBody = file_get_contents('php://input');
//        //回调的contentType
//        $contentType = 'application/x-www-form-urlencoded';
//        //回调的签名信息，可以验证该回调是否来自七牛
//        $authorization = $_SERVER['HTTP_AUTHORIZATION'];
//        //七牛回调的url，具体可以参考：https://developer.qiniu.com/kodo/manual/1206/put-policy
//        $url = 'http://172.30.251.210/upload_verify_callback.php';
//        $isQiniuCallback = $auth->verifyCallback($contentType, $authorization, $url, $callbackBody);
//        if ($isQiniuCallback) {
//            $resp = array('ret' => 'success');
//        } else {
//            $resp = array('ret' => 'failed');
//        }
//        echo json_encode($resp);
    }
}
