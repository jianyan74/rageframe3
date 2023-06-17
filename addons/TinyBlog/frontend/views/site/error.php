<?php

use yii\helpers\Url;
use yii\helpers\Html;
use addons\TinyBlog\frontend\widgets\sidebar\SidebarWidget;

$this->title = $name;

?>

<div class="main fixed">
    <div class="mask"></div>
    <div class="wrap">
        <!--当前位置-->
        <div class="sitemap">当前位置：<a href="<?= Url::to(['index/index']); ?>">首页</a>&nbsp;&gt;&nbsp;<a href="#">错误页面</a></div>
        <div class="content">
            <div class="block">
                <div class="post">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <div class="single viewall">
                        <?= nl2br(Html::encode($message)) ?>
                    </div>
                </div>
            </div>
        </div>
        <!--侧边栏-->
        <?= SidebarWidget::widget([]) ?>
    </div>
</div>
