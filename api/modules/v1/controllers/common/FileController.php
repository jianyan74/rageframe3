<?php

namespace api\modules\v1\controllers\common;

use common\traits\FileAction;
use api\controllers\OnAuthController;

/**
 * 资源上传控制器
 *
 * Class FileController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class FileController extends OnAuthController
{
    use FileAction;

    /**
     * @var string
     */
    public $modelClass = '';
}
