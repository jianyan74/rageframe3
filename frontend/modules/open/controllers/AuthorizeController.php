<?php

namespace frontend\modules\open\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use oauth2\components\Response;
use oauth2\entity\UserEntity;
use oauth2\repository\AuthCodeRepository;
use oauth2\repository\RefreshTokenRepository;
use oauth2\entity\ScopeEntity;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use GuzzleHttp\Psr7\ServerRequest;
use frontend\forms\LoginForm;

/**
 * 授权码模式(即先登录获取code,再获取token)
 *
 * Class AuthorizeController
 * @package frontend\modules\open\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AuthorizeController extends Controller
{
    /**
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * @throws \Exception
     */
    public function init()
    {
        parent::init();
        // 初始化存储库
        $authCodeRepository = new AuthCodeRepository(); // Interface: AuthCodeRepositoryInterface
        $refreshTokenRepository = new RefreshTokenRepository(); // Interface: RefreshTokenRepositoryInterface

        // 授权码授权类型初始化
        $grant = new AuthCodeGrant(
            $authCodeRepository,
            $refreshTokenRepository,
            new \DateInterval(Yii::$app->params['user.codeExpire']) // 设置授权码过期时间为10分钟
        );
        $grant->setRefreshTokenTTL(new \DateInterval(Yii::$app->params['user.refreshTokenExpire'])); // 设置刷新令牌过期时间1个月
        Yii::$app->services->oauth2Server->set($grant); // 写入服务
    }

    /**
     * @return string|void
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $server = Yii::$app->services->oauth2Server->get();
        $request = ServerRequest::fromGlobals();

        try {
            // 验证 HTTP 请求，并返回 authRequest 对象
            $authRequest = $server->validateAuthorizationRequest($request);
            // 此时应将 authRequest 对象序列化后存在当前会话(session)中
            Yii::$app->session->set('authRequest', serialize($authRequest));
        } catch (OAuthServerException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        } catch (\Exception $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }

        // 判断是否已登录
        if (!Yii::$app->user->isGuest) {
            return $this->render('remind', []);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->callback();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect([
            'index',
            'response_type' => Yii::$app->request->get('response_type'),
            'client_id' => Yii::$app->request->get('client_id'),
            'redirect_uri' => Yii::$app->request->get('redirect_uri'),
            'state' => Yii::$app->request->get('state'),
            'scope' => Yii::$app->request->get('scope'),
        ]);
    }

    /**
     * 授权登录
     *
     * @return void|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionAuthorization()
    {
        // 判断是否已登录
        if (!Yii::$app->user->isGuest) {
            return $this->callback();
        }

        return $this->redirect(['index']);
    }

    /**
     * @throws NotFoundHttpException
     *
     * return mix
     */
    protected function callback()
    {
        $server = Yii::$app->services->oauth2Server->get();
        $response = new Response();

        try {
            /** @var AuthorizationRequest $authRequest 在会话(session)中取出 authRequest 对象 */
            $authRequest = unserialize(Yii::$app->session->get('authRequest'));
            // 设置用户实体(userEntity)
            $user = new UserEntity();
            $user->setIdentifier(Yii::$app->user->id);
            $authRequest->setUser($user);

            // 设置权限范围
            $scopeEntity = new ScopeEntity();
            $scopeEntity->setIdentifier('basic_info');
            $authRequest->setScopes([$scopeEntity]);

            // true = 批准，false = 拒绝
            $authRequest->setAuthorizationApproved(true);
            // 完成后重定向至客户端请求重定向地址
            $server->completeAuthorizationRequest($authRequest, $response);
        } catch (OAuthServerException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        } catch (\Exception $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }
    }
}
