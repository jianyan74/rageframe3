<?php

namespace addons\Authority\api\modules\v1\controllers;

use Yii;
use api\controllers\OnAuthController;
use common\helpers\ResultHelper;

/**
 * Class AccreditController
 * @package addons\Authority\api\modules\v1\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AccreditController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     *
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['verify'];

    /**
     * @return array|mixed|string|\yii\data\ActiveDataProvider
     */
    public function actionVerify()
    {
        return ResultHelper::json(422, '系统未授权, 请联系管理员');

        $url = Yii::$app->request->post('url');
        if (empty($url)) {
            return ResultHelper::json(422, '系统未授权, 请联系管理员');
        }

        return 'ok';
    }
}
