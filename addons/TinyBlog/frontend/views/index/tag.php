<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use addons\TinyBlog\frontend\widgets\sidebar\SidebarWidget;

$this->title = $tag['title'];

?>

<div class="main fixed">
    <div class="mask"></div>
    <div class="wrap">
        <div class="sitemap">当前位置：<a href="<?= Url::to(['index/index']); ?>">首页</a>&nbsp;&gt;&nbsp;标签查询&nbsp;&gt;&nbsp;<a href="<?= Url::to(['index/tag', 'tag_id' => $tag['id']]); ?>"><?= Html::encode($tag['title']); ?></a></div>
        <?= $this->render('_list', [
                'models' => $models,
                'pages' => $pages,
        ]) ?>
        <!--侧边栏-->
        <?= SidebarWidget::widget([]) ?>
    </div>
</div>


