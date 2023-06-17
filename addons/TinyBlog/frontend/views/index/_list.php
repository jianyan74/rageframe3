<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

?>

<div class="content">
    <div class="block">
        <?php foreach ($models as $model){ ?>
            <div class="post item">
                <h2><a href="<?= Url::to(['index/view', 'id' => $model['id'], 'cate_id' => $model['cate_id']]); ?>"><?= $model['title']; ?></a></h2>
                <div class="info">
                    <span class="user"><?= $model['author']; ?></span>
                    <span class="date"><?= Yii::$app->formatter->asDatetime($model['created_at']); ?></span>
                    <span class="cate"><a href="<?= Url::to(['index/list', 'cate_id' => $model['cate_id']]); ?>"><?= $model['cate']['title'] ?? ''; ?></a></span>
                    <span class="view"><?= $model['view']; ?></span>
                </div>
                <?php if (!empty($model['cover'])) {?>
                    <div class="postimg">
                        <a href="<?= Url::to(['index/view', 'id' => $model['id'], 'cate_id' => $model['cate_id']]); ?>">
                            <img src="<?= $model['cover']; ?>" alt="<?= $model['title']; ?>"/>
                        </a>
                    </div>
                <?php } ?>
                <div class="intro isimg"><?= $model['description']; ?></div>
                <div><a href="<?= Url::to(['index/view', 'id' => $model['id'], 'cate_id' => $model['cate_id']]); ?>" class="readmore">查看全文</a></div>
            </div>
        <?php } ?>
        <?php if (empty($models)) { ?>
            <div class="sitemap">
                暂无数据
            </div>
        <?php } ?>
    </div>
    <!--分页导航-->
    <div class="pagebar">
        <?= LinkPager::widget([
            'pagination' => $pages
        ]);?>
    </div>
</div>
