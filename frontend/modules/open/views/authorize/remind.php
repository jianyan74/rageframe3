<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Have login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>have logged in recently:</p>
    <div class="row">
        <div class="col-lg-12">
           username：<?= Yii::$app->user->identity->username; ?> <br>
           automatic login after <span class="seconds">3</span> seconds <br>
            <?= Html::a('logout', [
                'logout',
                'response_type' => Yii::$app->request->get('response_type'),
                'client_id' => Yii::$app->request->get('client_id'),
                'redirect_uri' => Yii::$app->request->get('redirect_uri'),
                'state' => Yii::$app->request->get('state'),
                'scope' => Yii::$app->request->get('scope')
            ]) ?>
        </div>
    </div>
</div>

<script>
    var timer = setInterval(function () {
        var seconds = $('.seconds').text();
        seconds = parseInt(seconds) - 1;
        if (seconds < 0) {
            window.location.href = "<?= Url::to(['authorization']); ?>";
            return;
        }

        $('.seconds').text(seconds);
    }, 1000)
</script>
