<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use common\enums\AccessTokenGroupEnum;
use common\enums\MemberTypeEnum;
use common\helpers\ResultHelper;
use backend\forms\LoginForm;

/**
 * Class SiteController
 * @package backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SiteController extends Controller
{
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
                        'actions' => ['login', 'get-wechat-login-qr', 'qr', 'wechat-login', 'error', 'captcha'],
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
            ]
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

            return $this->renderPartial('login', [
                'model' => $model,
                'hasWechat' => Yii::$app->has('wechatService'), // 微信插件是否安装
            ]);
        }
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

        $auth = Yii::$app->services->memberAuth->findOauthClient(AccessTokenGroupEnum::WECHAT_MP, $data['extend']['openid'], MemberTypeEnum::MANAGER);
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
        try {
            $data = Yii::$app->wechatService->qrcode->syncCreateByData([
                'name' => '账号绑定',
                'model_type' => 1,
                'expire_seconds' => 5 * 60,
                'extend' => [
                    'type' => 'login',
                    'member_id' => -1,
                    'remind' => [
                        'success' => '登录成功, 操作时间: {time}',
                        'error' => '登录失败，未绑定账号, 操作时间: {time}',
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
