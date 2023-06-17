<?php

use yii\helpers\Url;
use yii\helpers\Html;
use addons\TinyBlog\frontend\widgets\sidebar\SidebarWidget;

$this->title = '详情';

?>

<div class="main fixed">
    <div class="mask"></div>
    <div class="wrap">
        <!--当前位置-->
        <div class="sitemap">当前位置：<a href="<?= Url::to(['index/index']); ?>">首页</a>&nbsp;&gt;&nbsp;<a href="<?= Url::to(['index/list', 'cate_id' => $model['cate_id']]); ?>"><?= Html::encode($model['cate']['title'] ?? ''); ?></a></div>
        <div class="content">
            <div class="block">
                <div class="post">
                    <h1><?= Html::encode($model['title']); ?></h1>
                    <div class="info">
                        <span class="user"><?= Html::encode($model['author']); ?></span>
                        <span class="date"><?= Yii::$app->formatter->asDatetime($model['created_at']); ?></span>
                        <span class="cate"><a href="<?= Url::to(['index/list', 'cate_id' => $model['cate_id']]); ?>"><?= Html::encode($model['cate']['title'] ?? ''); ?></a></span>
                        <span class="view"><?= $model['view']; ?></span>
                    </div>
                    <div class="single viewall">
                        <?php if (!empty($model['cover'])) {?>
                            <p style="text-align: center;">
                                <img alt="" src="<?= $model['cover']; ?>" style="width: 643px; height: 362px;">
                            </p>
                        <?php } ?>
                        <?= Html::decode($model['content']); ?>
                        <?php if (!empty($model['link'])) {?>
                            <a href="<?= $model['link']; ?>" target="_blank" style="color:#999;font-size:14px">查看原文</a>
                        <?php } ?>
                    </div>
                </div>
                <div class="pages">
                    <?php if (!empty($prev)) { ?>
                        <p>上一篇：<a href="<?= Url::to(['index/view', 'id' => $prev['id'], 'cate_id' => $prev['cate_id']]); ?>"><?= Html::encode($prev['title']); ?></a></p>
                    <?php } ?>
                    <?php if (!empty($next)) { ?>
                        <p>下一篇：<a href="<?= Url::to(['index/view', 'id' => $next['id'], 'cate_id' => $next['cate_id']]); ?>"><?= Html::encode($next['title']); ?></a></p>
                    <?php } ?>
                </div>
            </div>
            <!--相关文章，如果没有，就显示最新文章-->
            <div class="block">
                <div class="posttitle"><h4>推荐文章</h4></div>
                <div class="relatecon">
                    <?php foreach ($recommend as $value){ ?>
                        <div class="relate">
                            <?php if (!empty($value['cover'])) {?>
                                <div class="relateimg">
                                    <a href="<?= Url::to(['index/view', 'id' => $value['id'], 'cate_id' => $value['cate_id']]); ?>" title="<?= Html::encode($value['title']); ?>">
                                        <img src="<?= $value['cover']; ?>" alt="<?= Html::encode($value['title']); ?>">
                                    </a>
                                </div>
                            <?php } ?>
                            <div class="relateinfo">
                                <h3><a href="<?= Url::to(['index/view', 'id' => $value['id'], 'cate_id' => $value['cate_id']]); ?>" title="<?= Html::encode($value['title']); ?>"><?= Html::encode($value['title']); ?></a></h3>
                                <p class="isimg"><?= Html::encode($value['description']); ?></p></div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <!--评论-->
        </div>
        <!--侧边栏-->
        <?= SidebarWidget::widget([]) ?>
    </div>
</div>
