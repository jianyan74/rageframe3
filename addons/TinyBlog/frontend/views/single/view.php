<?php

use yii\helpers\Url;
use yii\helpers\Html;
use addons\TinyBlog\frontend\widgets\sidebar\SidebarWidget;

$this->title = $model['title'];

?>

<div class="main fixed">
    <div class="mask"></div>
    <div class="wrap">
        <!--当前位置-->
        <div class="sitemap">当前位置：<a href="<?= Url::to(['index/index']); ?>">首页</a>&nbsp;&gt;&nbsp;<a href="<?= Url::to(['single/view', 'single_id' => $model['id']]); ?>"><?= Html::encode($model['title']); ?></a></div>
        <div class="content">
            <div class="block">
                <div class="post">
                    <h1><?= Html::encode($model['title']); ?></h1>
                    <div class="single viewall">
                        <?= Html::decode($model['content']); ?>
                    </div>
                </div>
            </div>
        </div>
        <!--侧边栏-->
        <?= SidebarWidget::widget([]) ?>
    </div>
</div>
