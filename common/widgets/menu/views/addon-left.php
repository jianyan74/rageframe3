<?php

use common\helpers\Url;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;

/** @var array $addon */
$addonName = $addon['name'];
$addonName = StringHelper::toUnderScore($addonName);

?>

<div class="box box-solid rfAddonMenu">
    <?php if (!empty($menus)) { ?>
        <div class="box-header with-border pt-4 pl-3">
            <h3 class="rf-box-title">业务菜单</h3>
        </div>
        <div class="box-body no-padding" style="padding-top: 0">
            <ul class="nav nav-pills flex-column">
                <?php foreach ($menus as $vo) { ?>
                    <li class="nav-item">
                        <a href="<?= Url::to(ArrayHelper::merge([$vo['url']], $vo['params'])); ?>" class="nav-link">
                            <i class="<?= $vo['icon'] ? $vo['icon'] : 'fa fa-puzzle-piece'; ?> rf-i"></i> <?= $vo['title']; ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
            <div class="hr-line-dashed"></div>
        </div>
    <?php } ?>
</div>
