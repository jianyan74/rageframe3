<?php

namespace addons\Wechat\api\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use common\helpers\WechatHelper;
use api\controllers\OnAuthController;

/**
 * Class ReceiveMessageController
 * @package addons\Wechat\api\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ReceiveMessageController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     *
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['index'];

    /**
     * 处理微信消息
     *
     * @return array|mixed
     * @throws NotFoundHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException
     */
    public function actionIndex()
    {
        // 非格式化返回
        Yii::$app->params['triggerBeforeSend'] = false;

        $request = Yii::$app->request;
        switch ($request->getMethod()) {
            // 激活公众号
            case 'GET':
                if (WechatHelper::verifyToken($request->get('signature'), $request->get('timestamp'),
                    $request->get('nonce'))) {
                    return $request->get('echostr');
                }

                throw new NotFoundHttpException('签名验证失败.');
                break;
            // 接收数据
            case 'POST':
                $app = Yii::$app->wechat->getApp();
                $app->server->push(function ($message) {
                    try {
                        // 微信消息
                        Yii::$app->wechatService->message->setMessage($message);// 消息记录
                        Yii::$app->params['messageHistory'] = [
                            'openid' => $message['FromUserName'],
                            'type' => $message['MsgType'],
                            'event' => '',
                            'rule_id' => 0, // 规则id
                            'keyword_id' => 0, // 关键字id
                        ];

                        switch ($message['MsgType']) {
                            // '收到事件消息';
                            case 'event' :
                                $reply = $this->event($message);
                                break;
                            // '收到文字消息';
                            case 'text' :
                                $reply = Yii::$app->wechatService->message->text();
                                break;
                            // ... 其它消息(image、voice、video、location、link、file ...)
                            default :
                                $reply = Yii::$app->wechatService->message->other();
                                break;
                        }

                        Yii::$app->wechatService->messageHistory->save(Yii::$app->params['messageHistory'], Yii::$app->wechatService->message->getMessage());

                        return $reply;
                    } catch (\Exception $e) {
                        // 全局接收通知
                        if ($e->getCode() === 200) {
                            return $e->getMessage();
                        }

                        // 记录行为日志
                        Yii::$app->services->log->push(500, 'wechatApiReply', Yii::$app->services->base->getErrorInfo($e));

                        if (YII_DEBUG) {
                            return $e->getMessage();
                        }

                        return '系统出错，请联系管理员';
                    }
                });

                // 将响应输出
                $response = $app->server->serve();
                $response->send();
                break;
            default:
                throw new NotFoundHttpException('所请求的页面不存在.');
        }

        exit();
    }

    /**
     * 事件处理
     *
     * @param $message
     * @return bool|mixed
     * @throws NotFoundHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    protected function event($message)
    {
        Yii::$app->params['messageHistory']['event'] = $message['Event'];

        switch ($message['Event']) {
            // 关注事件
            case 'subscribe' :
                Yii::$app->wechatService->fans->follow($message['FromUserName']);

                // 判断是否是二维码关注
                if ($qrResult = Yii::$app->wechatService->qrcodeStat->scan($message)) {
                    $message['Content'] = $qrResult;
                    Yii::$app->wechatService->message->setMessage($message);

                    return Yii::$app->wechatService->message->text();
                }

                return Yii::$app->wechatService->message->follow();
                break;
            // 取消关注事件
            case 'unsubscribe' :
                Yii::$app->wechatService->fans->unFollow($message['FromUserName']);

                return false;
                break;
            // 二维码扫描事件
            case 'SCAN' :
                if ($qrResult = Yii::$app->wechatService->qrcodeStat->scan($message)) {
                    $message['Content'] = $qrResult;
                    Yii::$app->wechatService->message->setMessage($message);
                    return Yii::$app->wechatService->message->text();
                }
                break;
            // 上报地理位置事件
            case 'LOCATION' :

                // TODO 暂时不处理

                break;
            // 自定义菜单(点击)事件
            case 'CLICK' :
                $message['Content'] = $message['EventKey'];
                Yii::$app->wechatService->message->setMessage($message);

                return Yii::$app->wechatService->message->text();
                break;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function verbs()
    {
        return [
            'index' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'view' => ['GET', 'HEAD', 'OPTIONS'],
            'create' => ['POST', 'OPTIONS'],
            'update' => ['PUT', 'PATCH', 'OPTIONS'],
            'delete' => ['DELETE', 'OPTIONS'],
        ];
    }
}
