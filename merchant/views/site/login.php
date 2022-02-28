<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */

/* @var $model LoginForm */

use common\forms\LoginForm;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\captcha\Captcha;

$this->title = Yii::$app->params['adminTitle'];

?>

<style>
    .login-box {
        width: 360px;
        margin: 7% auto;
    }
</style>
<body class="login-page">
<div class="login-box">
    <div class="login-logo">
        <?= Html::encode(Yii::$app->params['adminTitle']); ?>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Welcome to</p>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <?= $form->field($model, 'username')->textInput(['placeholder' => '登录账号'])->label(false); ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => '登录密码'])->label(false); ?>
            <?php if ($model->scenario == 'captchaRequired') { ?>
                <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                    'template' => '<div class="row"><div class="col-sm-7">{input}</div><div class="col-sm-5">{image}</div></div>',
                    'imageOptions' => [
                        'alt' => '点击换图',
                        'title' => '点击换图',
                        'style' => 'cursor:pointer',
                    ],
                    'options' => [
                        'class' => 'form-control',
                        'placeholder' => '验证码',
                    ],
                ])->label(false); ?>
            <?php } ?>
            <?= $form->field($model, 'rememberMe')->checkbox() ?>
            <div class="form-group">
                <?= Html::submitButton('立即登录', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
            <div class="social-auth-links text-center">还没有帐号？<?= Html::a('申请入驻', ['register'], [
                    'class' => 'blue'
                ]); ?></div>
            <div class="social-auth-links text-center">
                <p><?= Html::encode(Yii::$app->services->config->backendConfig('web_copyright')); ?></p>
            </div>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
</body>

<script>
    //判断是否存在父窗口
    if (window.parent !== this.window) {
        parent.location.reload();
    }
</script>
