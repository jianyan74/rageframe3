<?php

use common\helpers\ImageHelper;
use common\widgets\menu\MenuLeftWidget;
use common\enums\ThemeLayoutEnum;

?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar main-sidebar-custom <?php if (Yii::$app->params['theme']['layout'] == ThemeLayoutEnum::DEFAULT) { ?>sidebar-dark-white elevation-2<?php } else { ?>rf-border-right<?php } ?>">
    <!-- Brand Logo -->
    <?php if (Yii::$app->params['theme']['layout'] == ThemeLayoutEnum::DEFAULT) { ?>
        <a href="" class="brand-link">
            <img src="<?= ImageHelper::defaultHeaderPortrait(Yii::$app->services->config->backendConfig('web_logo'), '@baseResources/img/logo.png'); ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-2">
            <span class="brand-text font-weight-light"><?= Yii::$app->params['adminTitle']; ?></span>
        </a>
    <?php } ?>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" style="padding-bottom: 60px">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-header">系统菜单</li>
                <?= MenuLeftWidget::widget(); ?>
                <?php if (!empty(Yii::$app->services->config->backendConfig('sys_related_links'))){ ?>
                    <!-- 相关链接 -->
                    <li class="nav-header">相关链接</li>
                    <li class="nav-item">
                        <a href="http://www.rageframe.com" class="nav-link" onclick="window.open('http://www.rageframe.com')">
                            <i class="nav-icon far fa-bookmark text-danger"></i>
                            <p class="text">系统官网</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="https://github.com/jianyan74/rageframe3/blob/master/docs/guide-zh-CN/README.md" class="nav-link" onclick="window.open('https://github.com/jianyan74/rageframe3/blob/master/docs/guide-zh-CN/README.md')">
                            <i class="nav-icon far fa-circle text-warning"></i>
                            <p>在线文档</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="https://jq.qq.com/?_wv=1027&k=5yvRLd7" class="nav-link" onclick="window.open('https://jq.qq.com/?_wv=1027&k=5yvRLd7')">
                            <i class="nav-icon far fa-circle text-info"></i>
                            <p>QQ交流群1</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="https://jq.qq.com/?_wv=1027&k=Wk663e9N" class="nav-link" onclick="window.open('https://jq.qq.com/?_wv=1027&k=Wk663e9N')">
                            <i class="nav-icon far fa-circle text-info"></i>
                            <p>QQ交流群2</p>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
</aside>
