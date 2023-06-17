<?php

namespace addons\TinyBlog\frontend\controllers;

/**
 * Class SiteController
 * @package addons\TinyBlog\frontend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
}
