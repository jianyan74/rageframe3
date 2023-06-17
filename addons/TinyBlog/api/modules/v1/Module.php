<?php

namespace addons\TinyBlog\api\modules\v1;

/**
 * Class Module
 * @package addons\TinyBlog\api\modules\v1 * @author jianyan74 <751393839@qq.com>
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'addons\TinyBlog\api\modules\v1\controllers';

    public function init()
    {
        parent::init();
    }
}