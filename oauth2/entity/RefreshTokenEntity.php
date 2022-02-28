<?php

namespace oauth2\entity;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

/**
 * Class RefreshTokenEntity
 * @package oauth2\entity
 * @author jianyan74 <751393839@qq.com>
 */
class RefreshTokenEntity implements RefreshTokenEntityInterface
{
    use RefreshTokenTrait, EntityTrait;
}
