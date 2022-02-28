<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * Class AdminLteDemoAsset
 * @package common\assets
 * @author jianyan74 <751393839@qq.com>
 */
class AdminLteDemoAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/dist';

    /**
     * @var string[]
     */
    public $js = [
        'js/demo.js',
    ];
}
