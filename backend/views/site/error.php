<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use common\helpers\Html;

$this->title = $name;
?>

<div class="error-page text-center" style="padding-top: 80px">
    <div style="height: 230px; width: 600px">
        <img src="/resources/img/no-data.png" alt="" width="230">
    </div>
    <h1 style="color: #869099"><?= Html::encode($code) ?></h1>
    <h2 style="color: #869099;padding-bottom: 10px"><?= Html::encode($name) ?></h2>
    <h5 style="color: #869099"><?= nl2br(Html::encode($message)) ?></h5>
    <!-- /.error-content -->
</div>
