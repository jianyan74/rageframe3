<?php

use common\helpers\Html;
use common\helpers\ImageHelper;

$roles = Yii::$app->services->rbacAuthRole->getRoles();
$menuCates = Yii::$app->services->menuCate->findAllInAuth(Yii::$app->id)

?>

<nav class="subfield-nav" style="height: 100%;">
    <!-- Left navbar links -->
    <ul class="navbar-nav rf-navbar-nav-left">
        <li class="pt-4 pb-4 text-center">
            <img src="<?= ImageHelper::defaultHeaderPortrait(Yii::$app->services->config->backendConfig('web_logo'), '@baseResources/img/logo.png'); ?>" alt="AdminLTE Logo" width="40" height="40" class="brand-image img-circle elevation-2">
        </li>
        <?php foreach ($menuCates as $cate){ ?>
            <li class="nav-item d-none d-sm-inline-block rfTopMenu" data-id="<?= $cate['id']; ?>" data-type="<?= $cate['type']; ?>">
                <a href="#" class="nav-link" title="<?= Html::encode($cate['title']); ?>">
                    <i class="rf-i fa <?= Html::encode($cate['icon']); ?>"></i>
                    <?= Html::encode($cate['title']); ?>
                </a>
            </li>
        <?php } ?>
    </ul>
</nav>
