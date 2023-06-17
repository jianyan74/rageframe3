<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */

/* @var $model LoginForm */

use common\forms\LoginForm;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\captcha\Captcha;
use backend\assets\BaseAsset;
use yii\helpers\Url;

BaseAsset::register($this);

$this->title = Yii::$app->params['adminTitle'];

?>

<style>
    .login-box {
        width: 360px;
        margin: 7% auto;
    }

    .wechat-qr-box {
        height: 178px;
        width: 178px;
        top: 10px
    }

    .wechat-qr-img {
        height: 178px;
        width: 178px;
        padding: 10px;
        border: 1px solid #e8eaec;
        margin-left: 80px
    }

    .wechat-qr-shade {
        background-color: rgba(0, 0, 0, 0.7);
        width: 100%;
        height: 100%;
        left: 80px;
        overflow: hidden;
        position: absolute;
        right: -2px;
        top: -2px;
        z-index: 10;
    }

    .wechat-qr-shade-loading-text,
    .wechat-qr-shade-lose-text {
        position: relative;
        top: 70px;
        color: #d2d2d2
    }
</style>

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
<body class="login-page">
<?php $this->beginBody() ?>
<div class="login-box">
    <div class="login-logo">
        <?= Html::encode(Yii::$app->params['adminTitle']); ?>
    </div>
    <!-- /.login-logo -->
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header border-bottom-0 <?= !empty($hasWechat) ? '' : 'hide'; ?>">
            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist" style="padding-left: 70px">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="pill" href="#custom-1">账号登录</a>
                </li>
                <li class="nav-item wechat-login">
                    <a class="nav-link" data-toggle="pill" href="#custom-2">二维码登录</a>
                </li>
            </ul>
        </div>
        <div class="card-body login-card-body">
            <div class="tab-content" id="custom-tabs-four-tabContent">
                <div class="tab-pane fade active show" id="custom-1">
                    <p class="login-box-msg <?= empty($hasWechat) ? '' : 'hide'; ?>">Welcome to</p>
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
                        <?= Html::submitButton('立即登录',
                            ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="tab-pane fade" id="custom-2">
                    <div class="text-center">
                        <span class="help">请使用 "微信扫一扫" 进行登录</span><br>
                        <div class="position-relative wechat-qr-box">
                            <img src="<?= Url::to(['qr', 'url' => 'loading'])?>" class="wechat-qr-img">
                            <div class="wechat-qr-shade">
                                <div class="wechat-qr-shade-loading-text pt-2">
                                    加载中...
                                </div>
                                <div class="wechat-qr-shade-lose-text hide">
                                    <span class="pb-3">二维码已过期</span><br>
                                    <span class="blue pointer" onclick="initWechatLoginQr()">刷新</span>
                                </div>
                            </div>
                        </div>
                        <div class="pt-3">
                            <span class="help wechat-lose hide">二维码已过期</span>
                            <span class="help wechat-expire-seconds">剩余有效期 <span id="expire-seconds">300</span> 秒</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="social-auth-links text-center">
                <p><?= Html::encode(Yii::$app->services->config->backendConfig('web_copyright')); ?></p>
            </div>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>

<script>
    //判断是否存在父窗口
    if (window.parent !== this.window) {
        parent.location.reload();
    }

    // 配置
    let config = {
        tag: "<?= Yii::$app->services->config->backendConfig('sys_tags') ?? false; ?>",
        isMobile: "<?= Yii::$app->params['isMobile'] ?? false; ?>",
    };

    var timer;
    var ticket;
    $('.wechat-login').click(function () {
        initWechatLoginQr();
    })

    // 启动重新
    function initWechatLoginQr() {
        clearInterval(timer);
        ticket = '';
        // 底部文字显示
        $(".wechat-expire-seconds").removeClass('hide');
        $(".wechat-lose").addClass('hide');
        // 遮罩(显示加载中)
        $('.wechat-qr-shade').removeClass('hide');
        $('.wechat-qr-shade-lose-text').addClass('hide');
        $('.wechat-qr-shade-loading-text').removeClass('hide');

        $.ajax({
            type: "get",
            url: "<?= Url::to(['get-wechat-login-qr'])?>",
            dataType: "json",
            success: function (data) {
                if (parseInt(data.code) !== 200) {
                    rfMsg(data.message);
                } else {
                    $('.wechat-qr-img').attr('src', '<?= Url::to(['qr'])?>' + '?url=' + data.data.url);
                    $('.wechat-qr-shade').addClass('hide');
                    $('#expire-seconds').text(data.data.expire_seconds);
                    timer = setInterval(setTime, 1000);
                    ticket = data.data.ticket;
                }
            }
        });
    }

    // 定时器启动
    function setTime() {
        var seconds = $("#expire-seconds").text();
        if (parseInt(seconds) > 0) {
            $("#expire-seconds").text(parseInt(seconds) - 1);

            // 判断登录
            $.ajax({
                type: "get",
                url: "<?= Url::to(['wechat-login'])?>",
                dataType: "json",
                data: {ticket: ticket},
                success: function (data) {
                    if (parseInt(data.code) === 200) {
                        window.location.href = "<?= Url::to(Yii::$app->getHomeUrl())?>";
                    }
                }
            });
        } else {
            // 底部文字显示
            $(".wechat-expire-seconds").addClass('hide');
            $(".wechat-lose").removeClass('hide');
            // 遮罩(显示二维码过期)
            $('.wechat-qr-shade').removeClass('hide');
            $('.wechat-qr-shade-lose-text').removeClass('hide');
            $('.wechat-qr-shade-loading-text').addClass('hide');
            // 去除定时器
            clearInterval(timer);
        }
    }
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
