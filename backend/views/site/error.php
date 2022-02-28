<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use common\helpers\Html;

$this->title = $name;
?>

<div class="error-page text-center" style="padding-top: 150px">
    <span class="icon iconfont iconicon-test" style="font-size: 200px"></span>
    <h1><?= Html::encode($code) ?></h1>
    <h2><?= Html::encode($name) ?></h2>
    <h5><?= nl2br(Html::encode($message)) ?></h5>
    <!-- /.error-content -->
</div>
