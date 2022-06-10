<?php

namespace api\controllers;

use common\enums\MemberTypeEnum;

/**
 * 后台 api 基类
 *
 * Class BackendOnAuthController
 * @package api\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class BackendOnAuthController extends OnAuthController
{
    /**
     * 用户类型
     *
     * @var int
     */
    protected $memberType = MemberTypeEnum::MANAGER;
}
