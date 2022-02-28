<?php

namespace services\rbac;

use Yii;

/**
 * Class AuthService
 * @package services\rbac
 * @author jianyan74 <751393839@qq.com>
 */
class AuthService
{
    /**
     * 是否超级管理员
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return in_array(Yii::$app->user->id, Yii::$app->params['adminAccount']);
    }
}
