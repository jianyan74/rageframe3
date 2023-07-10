<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>

<div class="sidebar fixed">
    <?php if (!empty($hot)) {?>
        <dl class="sidebox">
            <dt>热门文章</dt>
            <dd>
                <ul>
                    <?php foreach ($hot as $value){ ?>
                        <li><a href="<?= Url::to(['index/view', 'id' => $value['id'], 'cate_id' => $value['cate_id']]); ?>" title="<?= Html::encode($value['title']); ?>"><?= Html::encode($value['title']); ?></a></li>
                    <?php } ?>
                </ul>
            </dd>
        </dl>
    <?php } ?>
    <dl class="sidebox">
        <dt>最新文章</dt>
        <dd>
            <ul>
                <?php foreach ($newest as $item){ ?>
                    <li><a href="<?= Url::to(['index/view', 'id' => $item['id'], 'cate_id' => $item['cate_id']]); ?>" title="<?= Html::encode($item['title']); ?>"><?= Html::encode($item['title']); ?></a></li>
                <?php } ?>
            </ul>
        </dd>
    </dl>
    <?php if (!empty($tags)) {?>
        <dl id="divCatalog" class="sidebox">
            <dt>标签列表</dt>
            <dd>
                <ul class="tagslist">
                    <?php foreach ($tags as $tag){ ?>
                        <a href="<?= Url::to(['index/tag', 'tag' => $tag['title']]); ?>"><?= Html::encode($tag['title']); ?></a>
                    <?php } ?>
                </ul>
            </dd>
        </dl>
    <?php } ?>
    <?php if (!empty($friendlyLinks)) {?>
        <dl id="divCatalog" class="sidebox">
            <dt>友情链接</dt>    <dd>
                <ul>
                    <?php foreach ($friendlyLinks as $key => $friendlyLink){ ?>
                        <a href="<?= $friendlyLink['link']; ?>" target="_blank">
                            <span>
                                <?php if ($key > 0) {?>|<?php } ?>
                                <?= Html::encode($friendlyLink['title']); ?>
                            </span>
                        </a>
                    <?php } ?>
                </ul>
            </dd>
        </dl>
    <?php } ?>
</div>
