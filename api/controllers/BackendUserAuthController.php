<?php

namespace api\controllers;

use common\enums\MemberTypeEnum;

/**
 * 后台 api 基类
 *
 * Class BackendUserAuthController
 * @package api\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class BackendUserAuthController extends UserAuthController
{
    /**
     * 用户类型
     *
     * @var int
     */
    protected $memberType = MemberTypeEnum::MANAGER;
}
