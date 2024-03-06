<?php

namespace merchant\controllers;

use common\enums\AccessTokenGroupEnum;
use common\enums\MemberTypeEnum;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\helpers\ResultHelper;
use common\models\extend\SmsLog;
use common\enums\AppEnum;
use common\enums\OperatingTypeEnum;
use common\enums\WhetherEnum;
use common\traits\BaseAction;
use merchant\forms\SmsCodeForm;
use merchant\forms\LoginForm;
use merchant\forms\SignUpForm;
use addons\Merchants\common\models\SettingForm;

/**
 * Class SiteController
 * @package merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SiteController extends Controller
{
    use BaseAction;

    /**
     * @var string
     */
    public $layout = "@backend/views/layouts/blank";

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'get-wechat-login-qr', 'qr', 'wechat-login', 'register', 'sms-code', 'register-protocol', 'error', 'captcha'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                // 'layout' => '@backend/views/layouts/blank'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'maxLength' => 6, // 最大显示个数
                'minLength' => 6, // 最少显示个数
                'padding' => 5, // 间距
                'height' => 32, // 高度
                'width' => 100, // 宽度
                'offset' => 4, // 设置字符偏移量
                'backColor' => 0xffffff, // 背景颜色
                'foreColor' => 0x62a8ea, // 字体颜色
            ],
        ];
    }

    /**
     * 登录
     *
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLogin()
    {
        if (!Yii::$app->has('merchantsService')) {
            throw new UnauthorizedHttpException('未安装商户插件，请联系管理员');
        }

        if (!Yii::$app->user->isGuest) {
            // 记录行为日志
            Yii::$app->services->actionLog->create('login', '自动登录', 0, [], false);

            return $this->goHome();
        }

        $model = new LoginForm();
        $model->loginCaptchaRequired();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // 记录行为日志
            Yii::$app->services->actionLog->create('login', '账号登录', 0, [], false);

            return $this->goHome();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
                'hasWechat' => Yii::$app->has('wechatService'), // 微信插件是否安装
            ]);
        }
    }

    /**
     * 注册
     *
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionRegister()
    {
        if (!Yii::$app->has('merchantsService')) {
            throw new UnauthorizedHttpException('未安装商户插件，请联系管理员');
        }

        /** @var SettingForm $setting */
        $setting = Yii::$app->merchantsService->config->setting();
        // 判断开放注册
        if (empty($setting->register_apply)){
            throw new NotFoundHttpException('找不到页面');
        }

        $model = new SignUpForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->register()) {
                return $setting->register_auto_pass == WhetherEnum::ENABLED
                    ? $this->redirect(['login'])
                    : $this->message('等待管理员审核中...', $this->redirect(['login']));
            }

            return $this->redirect(['register']);
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'registerProtocolTitle' => $setting->register_protocol_title,
            'merchantCate' => Yii::$app->merchantsService->cate->findAll(),
            'authRoleEnter' => Yii::$app->services->rbacAuthRole->getMapList(AppEnum::MERCHANT, 0, ['operating_type' => OperatingTypeEnum::ENTER]),
        ]);
    }

    /**
     * 获取验证码
     *
     * @return int|mixed
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionSmsCode()
    {
        if (!Yii::$app->has('merchantsService')) {
            throw new UnauthorizedHttpException('未安装商户插件，请联系管理员');
        }

        $setting = Yii::$app->merchantsService->config->setting();
        // 判断开放注册
        if (empty($setting->register_apply)){
            throw new NotFoundHttpException('找不到页面');
        }

        $model = new SmsCodeForm();
        $model->attributes = Yii::$app->request->post();
        if (!$model->validate()) {
            return ResultHelper::json(422, Yii::$app->services->base->analysisErr($model->getFirstErrors()));
        }

        // 测试
        if (YII_DEBUG) {
            $code = rand(1000, 9999);
            $log = new SmsLog();
            $log = $log->loadDefaultValues();
            $log->attributes = [
                'mobile' => $model->mobile,
                'code' => $code,
                'member_id' => 0,
                'usage' => $model->usage,
                'error_code' => 200,
                'error_msg' => 'ok',
                'error_data' => '',
            ];
            $log->save();

            return ResultHelper::json(200, '发送成功', [
                'code' => $code
            ]);
        }

        // 发送短信
        $model->send();

        return ResultHelper::json(200, '发送成功', []);
    }

    /**
     * 注册协议
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionRegisterProtocol()
    {
        $setting = Yii::$app->merchantsService->config->setting();
        // 判断开放注册
        if (empty($setting->register_apply)){
            throw new NotFoundHttpException('找不到页面');
        }

        return $this->render($this->action->id, [
            'register_protocol_title' => $setting->register_protocol_title,
            'register_protocol' => $setting->register_protocol,
        ]);
    }

    /**
     * 微信登录
     *
     * @param $uuid
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionWechatLogin($ticket)
    {
        if (!Yii::$app->has('merchantsService')) {
            throw new UnauthorizedHttpException('未安装商户插件，请联系管理员');
        }

        $data = Yii::$app->wechatService->qrcode->findByWhere([
            'ticket' => $ticket
        ]);

        if (empty($data)) {
            return ResultHelper::json(422, '无效的ticket');
        }

        if ($data['end_time'] <= time()) {
            return ResultHelper::json(422, '无效的ticket');
        }

        if (empty($data['extend']['openid'])) {
            return ResultHelper::json(422, '未登录');
        }

        $auth = Yii::$app->services->memberAuth->findOauthClient(AccessTokenGroupEnum::WECHAT_MP, $data['extend']['openid'], MemberTypeEnum::MERCHANT);
        if (empty($auth) || empty($auth->member)) {
            return ResultHelper::json(422, '未绑定账号');
        }

        // 登录
        Yii::$app->user->login($auth->member);
        // 记录行为日志
        Yii::$app->services->actionLog->create('login', '二维码登录', 0, [], false);

        return ResultHelper::json(200, '登录成功');
    }

    /**
     * 微信登录
     *
     * @param $uuid
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionGetWechatLoginQr()
    {
        if (!Yii::$app->has('merchantsService')) {
            throw new UnauthorizedHttpException('未安装商户插件，请联系管理员');
        }

        try {
            $data = Yii::$app->wechatService->qrcode->syncCreateByData([
                'name' => '账号绑定',
                'model_type' => 1,
                'expire_seconds' => 5 * 60,
                'extend' => [
                    'type' => 'merchantLogin',
                    'member_id' => -1,
                    'remind' => [
                        'success' => '商户登录成功, 操作时间: {time}',
                        'error' => '登录失败，未绑定商户账号, 操作时间: {time}',
                    ]
                ],
            ]);

            $data->save();

            return ResultHelper::json(200, '返回登录', [
                'ticket' => $data['ticket'],
                'url' => $data['url'],
                'expire_seconds' => $data['expire_seconds'],
            ]);
        } catch (\Exception $e) {
            return ResultHelper::json(422, $e->getMessage());
        }
    }

    /**
     * 二维码显示
     *
     * @param $uuid
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionQr($url)
    {
        $qr = Yii::$app->get('qr');
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', $qr->getContentType());

        return $qr->setText($url)
            ->setErrorCorrectionLevel('quartile')
            ->setSize(200)
            ->setMargin(7)
            ->writeString();
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLogout()
    {
        Yii::$app->services->actionLog->create('logout', '退出登录');

        Yii::$app->user->logout();

        return $this->goHome();
    }
}
