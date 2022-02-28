<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * Class AdminLtePluginAsset
 * @package common\assets
 * @author jianyan74 <751393839@qq.com>
 */
class AdminLtePluginAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins';

    public $css = [
        'fontawesome-free/css/all.min.css',
        'overlayScrollbars/css/OverlayScrollbars.min.css',
        'sweetalert2-theme-bootstrap-4/bootstrap-4.min.css',
        'toastr/toastr.min.css',
    ];

    public $js = [
        'bootstrap/js/bootstrap.bundle.min.js',
        'overlayScrollbars/js/jquery.overlayScrollbars.min.js',
        ['jquery/jquery.min.js', 'position' => \yii\web\View::POS_HEAD],
        ['toastr/toastr.min.js', 'position' => \yii\web\View::POS_HEAD],
    ];

    public $depends = [
    ];
}
