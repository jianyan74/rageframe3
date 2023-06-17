<?php

use common\helpers\Html;
use addons\TinyBlog\frontend\widgets\nav\NavWidget;
use addons\TinyBlog\frontend\assets\AppAsset;

AppAsset::register($this);

/** @var \addons\TinyBlog\common\forms\SettingForm $setting */
$setting = Yii::$app->tinyBlogService->config->setting();

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($setting->title . ' - ' . $this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="index">
<?php $this->beginBody() ?>
<div class="wrapper">
    <?= NavWidget::widget([]) ?>
    <?= $content ?>
    <!--页面底部-->
    <div class="footer">
        <div class="fademask"></div>
        <div class="wrap">
            <h3><a href="https://beian.miit.gov.cn" target="_blank"><?= $setting->web_site_icp; ?></a></h3>
            <h4><?= $setting->web_copyright; ?></h4>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
<script type="text/javascript">
    $(".is-search").click(function () {
        $(".schfixed").toggleClass("on");
    });

    $(".menuico").click(function () {
        $(".menu").toggleClass("on");
    });

    $(".menuico").click(function () {
        $(".menuico").toggleClass("on");
    });

    var height = $(window).height() - 180;
    $(".main").css({
        'min-height': height + 'px'
    });
</script>
</html>
<?php $this->endPage() ?>
