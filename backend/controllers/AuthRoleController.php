<?php

namespace backend\controllers;

use common\enums\AppEnum;
use common\traits\AuthRoleTrait;
use common\models\rbac\AuthRole;

/**
 * Class AuthRoleController
 * @package backend\modules\base\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AuthRoleController extends BaseController
{
    use AuthRoleTrait;

    /**
     * @var AuthRole
     */
    public $modelClass = AuthRole::class;

    /**
     * 默认应用
     *
     * @var string
     */
    public $appId = AppEnum::BACKEND;

    /**
     * 权限来源
     *
     * false:所有权限，true：当前角色
     *
     * @var bool
     */
    public $sourceAuthChild = true;

    /**
     * 渲染视图前缀
     *
     * @var string
     */
    public $viewPrefix = '@backend/views/auth-role/';
}
