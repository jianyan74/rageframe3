<?php

namespace addons\WechatMini\api\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use common\helpers\WechatHelper;
use api\controllers\OnAuthController;
use addons\WechatMini\common\enums\video\EventEnum;

/**
 * Class ReceiveMessageController
 * @package addons\WechatMini\api\controllers
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
     * @param $action
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function beforeAction($action)
    {
        // 非格式化返回
        Yii::$app->params['triggerBeforeSend'] = false;

        return parent::beforeAction($action);
    }

    /**
     * @return array|mixed|string
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;

        switch ($request->getMethod()) {
            // 激活小程序
            case 'GET':
                if (WechatHelper::verifyTokenMiniProgram($request->get('signature'), $request->get('timestamp'), $request->get('nonce'))) {
                    return $request->get('echostr');
                }

                throw new NotFoundHttpException('签名验证失败.');
            // 接收数据
            case 'POST':
                Yii::$app->services->log->push(500, 'wechatMiniMsg', file_get_contents('php://input'));

                $message = file_get_contents('php://input');
                !is_array($message) && $message = Json::decode($message);
                // $message = Yii::$app->request->post();
                try {
                    switch ($message['MsgType']) {
                        case 'event' : // '收到事件消息';
                            $reply = $this->event($message);
                            break;
                    }

                    return $reply;
                } catch (\Exception $e) {
                    // 记录行为日志
                    Yii::$app->services->log->push(500, 'wechatMiniApiReply', Yii::$app->debris->getSysError($e));

                    if (YII_DEBUG) {
                        return $e->getMessage();
                    }

                    return '系统出错，请联系管理员';
                }
                break;
            default:
                throw new NotFoundHttpException('所请求的页面不存在.');
        }
    }

    /**
     * @param $message
     */
    public function event($message)
    {
        switch ($message['Event']) {
            // 商家取消开通自定义组件
            case EventEnum::OPEN_PRODUCT_ACCOUNT_REGISTER :

                break;
            // 商品审核结果/商品系统下架通知
            case EventEnum::OPEN_PRODUCT_SPU_AUDIT :
                $baseProduct = Yii::$app->wechatMiniService->extendVideoSpu->view($message['out_product_id']);
                $spu = $baseProduct['spu'];
                if ($product = Yii::$app->wechatMiniService->videoSpu->findByOutProductId($message['out_product_id'])) {
                    $product->status = $message['status'];
                    $product->product_id = $spu['product_id'];
                    $product->edit_status = $spu['edit_status'];
                    $product->info_version = $spu['info_version'];
                    $product->reject_reason = $message['reject_reason'];
                    $product->save();
                }
                break;
            // 类目审核结果
            case EventEnum::OPEN_PRODUCT_CATEGORY_AUDIT :
                Yii::$app->wechatMiniService->videoAudit->callbackCate($message['audit_id'], $message['status'], $message['reject_reason']);
                break;
            // 品牌审核结果
            case EventEnum::OPEN_PRODUCT_BRAND_AUDIT :
                Yii::$app->wechatMiniService->videoAudit->callbackBrand($message['audit_id'], $message['status'], $message['reject_reason'], $message['brand_id']);
                break;
            // 场景审核结果
            case EventEnum::OPEN_PRODUCT_SCENE_GROUP_AUDIT :

                break;
            // 分享员绑定解绑通知
            case EventEnum::MINIPROGRAM_SHARER_BIND_STATUS_CHANGE :

                break;
            // 用户领券通知
            case EventEnum::OPEN_PRODUCT_RECEIVE_COUPON :

                break;
        }

        return 'success';
    }
}
