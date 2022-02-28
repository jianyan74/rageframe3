<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * Class AdminLteAsset
 * @package common\assets
 * @author jianyan74 <751393839@qq.com>
 */
class AdminLteAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/dist';

    public $css = [
        'css/adminlte.min.css',
    ];

    public $js = [
        'js/adminlte.min.js',
    ];

    public $depends = [
        AdminLtePluginAsset::class
    ];
}
