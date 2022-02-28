<?php

namespace merchant\controllers;

use Yii;
use yii\web\NotFoundHttpException;
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

    public function init()
    {
        if (!Yii::$app->has('merchantsService')) {
            throw new UnauthorizedHttpException('未安装商户插件，请联系管理员');
        }

        parent::init();
    }

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
                        'actions' => ['login', 'register', 'sms-code', 'register-protocol', 'error', 'captcha'],
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
