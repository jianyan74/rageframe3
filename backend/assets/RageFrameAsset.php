<?php

namespace backend\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Class RageFrameAsset
 * @package backend\assets
 * @author jianyan74 <751393839@qq.com>
 */
class RageFrameAsset extends AssetBundle
{
    public $basePath = '@root/web/resources';

    public $baseUrl = '@baseResources';

    public $css = [
        'https://at.alicdn.com/t/font_2524206_p9h2khogn8l.css',
        'plugins/bootstrap-table/bootstrap-table.min.css',
        'plugins/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css',
        'plugins/malihu-custom-scrollbar/jquery.mCustomScrollbar.css',
        'css/rageframe3.css',
        'css/rageframe.css',
        'css/rageframe.widgets.css',
        'plugins/cropper/cropper.min.css',
        'plugins/Ionicons/css/ionicons.min.css',
        'plugins/fancybox/jquery.fancybox.min.css', // 图片查看
    ];

    public $js = [
        'plugins/cropper/cropper.min.js',
        'plugins/fancybox/jquery.fancybox.min.js',
        'plugins/sweetalert/sweetalert.min.js',
        'plugins/clipboard/clipboard.min.js',
        'plugins/bootstrap-table/bootstrap-table.js',
        'plugins/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js',
        'plugins/malihu-custom-scrollbar/jquery.mCustomScrollbar.concat.min.js',
        'plugins/layer/layer.js',
        'js/template.js',
        'js/rageframe.js',
        'js/rageframe.widgets.js',
    ];

    public function init()
    {
        if (empty(Yii::$app->params['notRequireVue'])) {
            $this->js[] = ['js/vue.js', 'position'=>\yii\web\View::POS_HEAD];
        }

        parent::init();
    }
}
