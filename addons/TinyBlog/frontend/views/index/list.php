<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use addons\TinyBlog\frontend\widgets\sidebar\SidebarWidget;

$this->title = $cate['title'];

?>

<div class="main fixed">
    <div class="mask"></div>
    <div class="wrap">
        <div class="sitemap">当前位置：<a href="<?= Url::to(['index/index']); ?>">首页</a>&nbsp;&gt;&nbsp;<a href="<?= Url::to(['index/list', 'cate_id' => $cate['id']]); ?>"><?= Html::encode($cate['title']); ?></a></div>
        <?= $this->render('_list', [
            'models' => $models,
            'pages' => $pages,
        ]) ?>
        <!--侧边栏-->
        <?= SidebarWidget::widget([]) ?>
    </div>
</div>


