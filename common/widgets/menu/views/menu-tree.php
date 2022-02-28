<?php

use common\helpers\Url;
use common\helpers\Html;
use common\enums\StatusEnum;
?>

<?php foreach ($menus as $item) { ?>
    <li class="nav-item hide rfLeftMenu rfLeftMenu-<?= $item['cate_id']; ?> <?= (isset($item['cate']['is_default_show']) && $item['cate']['is_default_show'] == StatusEnum::ENABLED) ? 'is_default_show' : ''; ?>">
        <?php if (!empty($item['-'])) { ?>
            <a href="#" class="nav-link J_menuItem">
                <i class="nav-icon fa rf-i <?= $level == 1 ? $item['icon'] : ''; ?>"></i>
                <p>
                    <?= Html::encode($item['title']); ?>
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <?= $this->render('menu-tree', [
                    'menus' => $item['-'],
                    'level' => $level + 1,
                ]) ?>
            </ul>
        <?php } else { ?>
            <a href="<?= $item['fullUrl'] == '#' ? '' : Url::to($item['fullUrl']); ?>" class="nav-link J_menuItem">
                <i class="fa rf-i nav-icon <?= $level == 1 ? $item['icon'] : ''; ?> <?= $level > 2 ? 'ml-4' : ''; ?>"></i>
                <p><?= Html::encode($item['title']); ?></p>
            </a>
        <?php } ?>
    </li>
<?php } ?>
