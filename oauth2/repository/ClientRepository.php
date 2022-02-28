<?php

namespace oauth2\repository;

use Yii;
use yii\web\UnprocessableEntityHttpException;
use oauth2\entity\ClientEntity;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * Class ClientRepository
 * @package oauth2
 * @author jianyan74 <751393839@qq.com>
 */
class ClientRepository implements ClientRepositoryInterface
{
    /**
     * Get a client.
     *
     * @param string $clientIdentifier The client's identifier
     *
     * @return ClientEntityInterface|null
     */
    public function getClientEntity($clientIdentifier)
    {
        if (!($clientModel = Yii::$app->services->oauth2Client->findByClientId($clientIdentifier))) {
            throw new UnprocessableEntityHttpException('找不到 Client Id');
        }

        // 返回客户端信息
        $client = new ClientEntity();
        $client->setIdentifier($clientIdentifier);
        $client->setName($clientModel['title']);
        // 校验回调域名
        if (!($redirect_uri = Yii::$app->request->get('redirect_uri'))) {
            $redirect_uri = Yii::$app->request->post('redirect_uri');
        }

        if (!$redirect_uri) {
            $client->setRedirectUri($clientModel['redirect_uri']);
        } else {
            $client->setRedirectUri($redirect_uri);
        }

        // $client->setGrantType($grantType);

        return $client;
    }

    /**
     * Validate a client's secret.
     *
     * @param string      $clientIdentifier 客户端唯一标识符
     * @param null|string $clientSecret     代表客户端密钥，是客户端事先在授权服务器中注册时得到的
     * @param null|string $grantType        代表授权类型，根据类型不同，验证方式也不同
     *
     * @return bool
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        if (!($clientModel = Yii::$app->services->oauth2Client->findByClientId($clientIdentifier))) {
            throw new UnprocessableEntityHttpException('找不到 Client Id');
        }

        if ($clientModel['client_secret'] !== $clientSecret) {
            throw new UnprocessableEntityHttpException('Client Secret 错误');
        }

        return true;
    }
}
