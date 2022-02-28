<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\rbac\AuthItemChild;
use common\models\rbac\AuthRole;

/**
 * Class AuthCreateController
 *
 * php ./yii auth-create/index
 *
 * @package addons\Merchants\console\controllers
 */
class AuthCreateController extends Controller
{
    public $data = [
        [
            'item_id' => 0,
            'name' => '/merchants/archives/*',
            'app_id' => 'merchant',
            'is_addon' => 1,
            'addon_name' => 'Merchants',
        ],
    ];

    /**
     * 添加权限
     */
    public function actionIndex()
    {
        $role = AuthRole::find()->select('id')->asArray()->all();
        foreach ($role as $item) {
            foreach ($this->data as $datum) {
                $model = new AuthItemChild();
                $model->attributes = $datum;
                $model->role_id = $item['id'];
                $model->save();
            }
        }
    }
}
