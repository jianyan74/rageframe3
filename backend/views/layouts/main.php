<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use backend\assets\BaseAsset;
use backend\widgets\Alert;

BaseAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrapper-content">
    <!-- Content Header (Page header) -->
    <section class="content-header" style="padding-bottom: 0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <a href="<?= Yii::$app->request->getUrl(); ?>" class="rfHeaderFont">
                        <i class="icon ion-loop"></i> 刷新
                    </a>
                    <?php if (Yii::$app->request->referrer != Yii::$app->request->hostInfo . Yii::$app->request->getBaseUrl() . '/') { ?>
                        <a href="javascript:history.go(-1)" class="rfHeaderFont">
                            <i class="icon ion-reply"></i> 返回
                        </a>
                    <?php } ?>
                </div>
                <div class="col-sm-6">
                    <?= Breadcrumbs::widget([
                        'tag' => 'ol',
                        'homeLink' => [
                            'label' => '<i class="fa fa-tachometer-alt" style="font-size: 12px"></i> ' . Yii::$app->params['adminAcronym'],
                            'url' => "",
                        ],
                        'options' => [
                            'class' => 'float-sm-right',
                        ],
                        'encodeLabels' => false,
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <?= $content; ?>
        <!-- /.card -->
    </section>
    <?= Alert::widget(); ?>
    <!-- /.content -->
</div>

<?= $this->render('_footer') ?>
<?= $this->render('_common') ?>

<script>
    // 配置
    let config = {
        tag: true,
        isMobile: "<?= Yii::$app->params['isMobile'] ?? false; ?>",
    };

    $(function () {
        setTimeout(function () {
            $('[data-toggle="tooltip"]').tooltip()
        }, 50)
    })
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
