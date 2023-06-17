<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */

use yii\bootstrap4\Html;
use backend\assets\BaseAsset;
use backend\widgets\Alert;

BaseAsset::register($this);

?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <?= $content; ?>
    <?= Alert::widget(); ?>
    <?= $this->render('_common') ?>
    <script>
        // 配置
        let config = {
            tag: "<?= Yii::$app->services->config->backendConfig('sys_tags') ?? false; ?>",
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
<?php $this->endPage() ?>
