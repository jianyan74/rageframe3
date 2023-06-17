<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\enums\StatusEnum;
use common\helpers\AddonHelper;

/** @var \addons\TinyBlog\common\forms\SettingForm $setting */
$setting = Yii::$app->tinyBlogService->config->setting();

?>

<!--页面头部-->
<div class="header fixed">
    <div class="wrap">
        <div class="logo on"><a href="<?= Url::to(['index/index']); ?>"><img src="<?= !empty($setting->logo) ? $setting->logo : AddonHelper::file('images/logo.png')?>" alt="RF"/></a>
        </div>
        <div class="head">
            <div class="menuico"><span></span><span></span><span></span></div>
            <div class="menu">
                <ul>
                    <li class="navbar-item <?= $isIndex == StatusEnum::ENABLED ? 'on' : ''; ?>"><a href="<?= Url::to(['index/index']); ?>" title="Home page">首页</a></li>
                    <?php foreach ($cate as $item) { ?>
                        <li class="navbar-item <?= $item['select'] == StatusEnum::ENABLED ? 'on' : ''; ?>"><a href="<?= Url::to(['index/list', 'cate_id' => $item['id']]); ?>"><?= Html::encode($item['title']); ?></a></li>
                    <?php } ?>
                    <?php foreach ($single as $value) { ?>
                        <li class="navbar-item <?= $value['select'] == StatusEnum::ENABLED ? 'on' : ''; ?>"><a href="<?= Url::to(['single/view', 'single_id' => $value['id']]); ?>"><?= Html::encode($value['title']); ?></a></li>
                    <?php } ?>
                </ul>
                <div class="schico statefixed">
                    <a href="javascript:void(0)" class="is-search"></a>
                    <div class="schfixed">
                        <form name="search" method="get" action="<?= Url::to(['index/index']); ?>">
                            <input type="text" name="keyword" placeholder="搜索..." class="schinput">
                            <button type="submit" class="btn"></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
