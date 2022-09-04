<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\bootstrap4\Html;
use yii\helpers\Url;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode(Yii::$app->params['adminTitle']);?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition sidebar-mini layout-fixed" style="overflow:hidden">
    <?php $this->beginBody() ?>
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- 头部区域 -->
        <?= $this->render('_header'); ?>
        <!-- 左侧菜单栏 -->
        <?= $this->render('_left'); ?>
        <!-- 主体内容区域 -->
        <?= $this->render('_content'); ?>
        <!-- 右边控制栏 -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <?= Html::jsFile('@baseResources/js/contabs.js'); ?>
        <script>
            // 配置
            let config = {
                tag: "<?= Yii::$app->services->config->backendConfig('sys_tags') ?? false; ?>",
                isMobile: "<?= Yii::$app->params['isMobile'] ?? false; ?>",
            };

            /* 主题布局切换 */
            $(document).on("change", "#rfTheme", function () {
                var layout = $('#rfTheme').val();
                window.location.href = '<?= Url::to(['theme/update'])?>' + '?layout=' + layout;
            });
        </script>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
