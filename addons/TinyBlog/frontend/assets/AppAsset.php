<?php

namespace addons\TinyBlog\frontend\assets;

use yii\web\AssetBundle;

/**
 * 静态资源管理
 *
 * Class AppAsset
 * @package addons\TinyBlog\frontend\assets
 */
class AppAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@addons/TinyBlog/frontend/resources/';

    public $css = [
        'css/style.css'
    ];

    public $js = [
        ['js/jquery-2.2.4.min.js', 'position' => \yii\web\View::POS_HEAD]
    ];

    public $depends = [
    ];
}
