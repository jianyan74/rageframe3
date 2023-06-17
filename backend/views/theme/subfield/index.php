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
    <body class="layout-boxed hold-transition sidebar-mini layout-fixed" style="overflow:hidden">
    <?php $this->beginBody() ?>
    <style>
        .layout-boxed .wrapper,
        .layout-boxed .wrapper::before {
            margin-left: 100px;
            max-width: 100%;
            overflow: hidden;
        }

        .os-padding {
            background-color: #ffffff;
        }

        .rf-subfield-left {
            width:100px;
            position: absolute;
            background-color: #191a23;
            height: 100vh;
            overflow-y: hidden;
            z-index: 1038;
        }

        .rf-subfield-left nav .nav-item {
            margin: 0 5px 2px 20px;
            display: flex;
            cursor: pointer;
            white-space: nowrap;
            overflow: hidden;
            border-radius: 2px;
            font-size: 14px;
            height: 40px;
        }

        .sidebar .nav-link p,
        .nav-sidebar .nav-item > .nav-link {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-treeview .nav-link {
            padding: .5rem 1.2rem;
        }

        .rf-subfield-left nav li a {
            padding: 0 0 0 8px;
            color: hsla(0,0%,100%,.7);
        }

        .rf-subfield-left nav .nav-item a i {
            padding-right: 6px;
        }

        .rfTopMenuHover .nav-link {
            color: #fff;
        }

        .nav-sidebar > .nav-item .nav-icon {
            display: none;
        }

        .main-header .logo, .main-sidebar {
            width: 130px;
        }

        body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .content-wrapper,
        body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-footer,
        body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-header {
            transition: margin-left .3s ease-in-out;
            margin-left: 130px;
        }

        @media (max-width: 767px) {
            body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .content-wrapper,
            body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-footer,
            body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-header {
                transition: margin-left .3s ease-in-out;
                margin-left: 0;
            }

            .sidebar-open .main-sidebar,
            .sidebar-open .main-sidebar::before {
                margin-left: 130px;
                height: 100%;
            }
        }

        .layout-fixed .brand-link {
            width: 130px;
        }

        .layout-navbar-fixed .wrapper .main-sidebar:hover .brand-link {
            transition: width 0.3s ease-in-out;
            width: 130px;
        }

        .sidebar-mini.sidebar-collapse.layout-fixed .main-sidebar:hover .brand-link {
            width: 130px;
        }

        .sidebar-mini.sidebar-collapse .main-sidebar.sidebar-focused,
        .sidebar-mini.sidebar-collapse .main-sidebar:hover {
            width: 130px;
        }

        .sidebar-collapse.sidebar-mini .main-sidebar:hover .nav-link,
        .sidebar-collapse.sidebar-mini-md .main-sidebar:hover .nav-link,
        .sidebar-collapse.sidebar-mini-xs .main-sidebar:hover .nav-link {
            width: calc(130px - .5rem * 2);
            transition: width ease-in-out .3s;
        }

        .sidebar-mini .main-sidebar .nav-link,
        .sidebar-mini-md .main-sidebar .nav-link,
        .sidebar-mini-xs .main-sidebar .nav-link {
            width: calc(130px - .5rem * 2);
            transition: width ease-in-out .3s;
        }

        .mCSB_inside > .mCSB_container {
            margin-right: 0;
        }

        .mCSB_scrollTools .mCSB_draggerContainer {
            display: none;
        }
    </style>

    <div class="rf-subfield-left">
        <?= $this->render('_fence'); ?>
    </div>
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- 头部区域 -->
        <?= $this->render('_header'); ?>
        <!-- 左侧菜单栏 -->
        <?= $this->render('@backend/views/theme/default/_left'); ?>
        <!-- 主体内容区域 -->
        <?= $this->render('@backend/views/theme/default/_content'); ?>
        <!-- 底部区域 -->

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

            $(document).ready(function () {
                autoNav();
            });

            $(window).resize(function () {
                autoNav();
            });

            function autoNav() {
                $(".subfield-nav").mCustomScrollbar({
                    scrollInertia: 0,
                    autoHideScrollbar: true,
                });
            }
        </script>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
