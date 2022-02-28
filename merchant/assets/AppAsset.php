<?php

namespace merchant\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package merchant\assets
 * @author jianyan74 <751393839@qq.com>
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
    ];

    public $js = [
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'common\assets\AdminLteAsset',
        'common\assets\AdminLteDemoAsset',
        'backend\assets\RageFrameAsset',
    ];
}
