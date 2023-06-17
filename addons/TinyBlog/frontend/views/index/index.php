<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use common\helpers\AddonHelper;
use addons\TinyBlog\frontend\widgets\sidebar\SidebarWidget;

$this->title = '首页';

?>

<div class="main fixed">
    <?php if (!empty($adv)) {?>
        <?php if ($adv['jump_link']) {?>
            <a href="<?= $adv['jump_link']; ?>" target="_blank">
                <div class="banner" data-type="display" data-speed="2" style="height:320px;background-image:url(<?= $adv['cover']; ?>);">
                    <div class="wrap"><h2><?= Html::encode($adv['name']); ?></h2></div>
                </div>
            </a>
        <?php } else { ?>
            <div class="banner" data-type="display" data-speed="2" style="height:320px;background-image:url(<?= $adv['cover']; ?>);">
                <div class="wrap"><h2><?= Html::encode($adv['name']); ?></h2></div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="banner" data-type="display" data-speed="2" style="height:320px;background-image:url(<?= AddonHelper::file('/images/banner.jpg'); ?>);">
            <div class="wrap"><h2>纷纷万事，直道而行！</h2></div>
        </div>
    <?php } ?>
    <div class="wrap">
        <?= $this->render('_list', [
            'models' => $models,
            'pages' => $pages,
        ]) ?>
        <!--侧边栏-->
        <?= SidebarWidget::widget([]) ?>
    </div>
</div>


