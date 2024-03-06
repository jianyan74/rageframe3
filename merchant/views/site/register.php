<?php

use common\helpers\Html;
use yii\helpers\Url;
use common\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->title = '商家入驻';

?>

<style>
    .register-box {
        width: 360px;
        margin: 7% auto;
    }

    .register-logo {
        font-size: 35px;
        text-align: center;
        margin-bottom: 25px;
        font-weight: 300;
    }

    .register-box-body {
        background: #fff;
        padding: 20px;
        border-top: 0;
        color: #666;
    }
</style>

<body class="register-page">
<div class="register-box">
    <div class="register-logo">
        <?= Html::a(Html::encode($this->title),
            ['/']); ?>
    </div>
    <div class="register-box-body">
        <p class="login-box-msg">商家入驻</p>
        <?php $form = ActiveForm::begin([
            'id' => 'register-form',
        ]); ?>
        <?= $form->field($model, 'title')->textInput(['placeholder' => '店铺名称'])->label(false); ?>
        <?= $form->field($model, 'cate_id')->widget(common\widgets\cascader\Cascader::class, [
            'data' => $merchantCate,
            'options' => [
                'style' => 'width:100%',
                'placeholder' => '请选择主营行业',
            ]
        ])->label(false); ?>
        <?= $form->field($model, 'auth_role_id')->dropDownList(ArrayHelper::merge(['' => '请选择开店套餐'], $authRoleEnter))->label(false); ?>
        <?= $form->field($model, 'mobile')->textInput(['placeholder' => '手机号码', 'id' => 'mobile'])->label(false); ?>
        <?= $form->field($model, 'code', [
            'template' => "
        <div class='input-group'>
            {input}
            <span class='input-group-btn'>
                 <button type='button' class='btn btn-white btn-flat'>获取验证码</button>
            </span>
        </div>\n{hint}\n{error}",
        ])->textInput(['placeholder' => '验证码'])->label(false); ?>
        <?= $form->field($model, 'username')->textInput(['placeholder' => '用户名'])->label(false); ?>
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => '用户密码'])->label(false); ?>
        <?= $form->field($model, 're_pass')->passwordInput(['placeholder' => '确认密码'])->label(false); ?>
        <div class="form-group field-signupform-rememberme has-error">
            <input type="hidden" name="SignUpForm[rememberMe]" value="0">
            <label>
                <input type="checkbox" id="signupform-rememberme" name="SignUpForm[rememberMe]" value="1">
                <?= '我同意' . \yii\helpers\Html::a('《' . $registerProtocolTitle . '》', ['register-protocol'], [
                    'target' => '_blank',
                    'class' => 'blue'
                ])?>
            </label>
            <div class="help-block"><?= $model->getFirstError('rememberMe')?></div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('马上注册', ['class' => 'btn btn-primary btn-block']) ?>
        </div>
        <?php ActiveForm::end(); ?>
        <div class="social-auth-links text-center">已有账号？<?= Html::a('立即登录', ['login'], [
                'class' => 'blue'
            ]); ?></div>
        <div class="social-auth-links text-center">
            <p><?= Html::encode(Yii::$app->services->config->backendConfig('web_copyright')); ?></p>
        </div>
    </div>
    <!-- /.form-box -->
</div>
</body>

<script>
    var time = 0;
    $('.btn-flat').click(function () {
        var mobile = $('#mobile').val();
        if (mobile.length === 0) {
            rfMsg('请输入手机号码');

            return;
        }

        if (mobile.length !== 11) {
            rfMsg('请输入正确的手机号码');

            return;
        }

        if (time <= 0) {
            $.ajax({
                type: "post",
                url: "<?= Url::to(['sms-code'])?>",
                dataType: "json",
                data: {mobile: mobile, usage: 'merchant-register'},
                success: function (result) {
                    if (parseInt(result.code) === 200) {
                        if (result.data.code) {
                            rfMsg('你的验证码为: ' + result.data.code);
                        }
                    } else {
                        rfMsg(result.message);
                    }
                }
            });

            time = 60;
            getRandomCode();
        }
    })

    //倒计时
    function getRandomCode() {
        if (time === 0) {
            $('.btn-flat').text('获取验证码');
            return;
        } else {
            time--;
            $('.btn-flat').text('剩余' + time + "秒");
        }
        setTimeout(function() {
            getRandomCode();
        },1000);
    }
</script>
