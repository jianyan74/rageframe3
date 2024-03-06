<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\authclient\OAuth2;
use yii\authclient\clients\Facebook;
use api\controllers\OnAuthController;
use common\helpers\ResultHelper;

/**
 * 第三方授权登录
 *
 * Class ThirdPartyController
 * @package api\modules\v1\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ThirdPartyController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['auth', 'code'];

    /**
     * @var array
     */
    protected $_clients = [];

    /**
     * @return void
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        /** @var Facebook $oauthClient */
        $oauthClient = new Facebook();
        $oauthClient->clientId = '';
        $oauthClient->clientSecret = '';
        // 获取授权内容
        $oauthClient->attributeNames = [
            // 'id',
            'name',
            'email',
            // 'first_name',
            // 'last_name',
        ];
        $this->_clients['facebook'] = $oauthClient;
    }

    /**
     * @return array
     */
    public function actionAuth($type)
    {
        /** @var OAuth2|Facebook $oauthClient */
        if (empty($oauthClient = $this->_clients[$type])) {
            return ResultHelper::json(422, '暂不支持该登录类型');
        }

        return [
            'url' => $oauthClient->buildAuthUrl(),
        ];
    }

    /**
     * @param $type
     * @param $code
     * @return array|mixed|void
     * @throws \yii\web\HttpException
     */
    public function actionCode($type, $code)
    {
        /** @var OAuth2|Facebook $oauthClient */
        if (empty($oauthClient = $this->_clients[$type])) {
            return ResultHelper::json(422, '暂不支持该登录类型');
        }

        $oauthClient->fetchAccessToken($code);
        // 获取登录的用户信息
        $attributes = $oauthClient->getUserAttributes();
        // TODO Login
    }
}
