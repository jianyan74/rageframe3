<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;

$this->title = '小程序配置';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "<div class='row'><div class='col-2 text-right'>{label}</div><div class='col-5'>{input}\n{hint}\n{error}</div></div>",
                ],
            ]); ?>
            <div class="box-body">
                <div class="col-sm-12">
                    <blockquote>
                        <p>开发设置</p>
                    </blockquote>
                    <?= $form->field($model, 'wechat_mini_app_id')->textInput(); ?>
                    <?= $form->field($model, 'wechat_mini_secret')->textInput()->hint('AppID(小程序ID)和AppSecret(小程序密钥)来自于您申请的小程序账号，使用小程序账号密码登录公众平台，在开发->开发设置中可以找到'); ?>
                    <blockquote>
                        <p>服务器配置信息</p>
                    </blockquote>
                    <div class="form-group m-b-md">
                        <div class="row">
                            <div class="col-2 text-right">
                                <label class="control-label">request合法域名 :</label>
                            </div>
                            <div class="col-10">
                                <span>https://<?= Yii::$app->request->getHostName() ?></span>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-b-md">
                        <div class="row">
                            <div class="col-2 text-right">
                                <label class="control-label">socket合法域名 :</label>
                            </div>
                            <div class="col-10">
                                <span>wss://<?= Yii::$app->request->getHostName() ?></span>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-b-md">
                        <div class="row">
                            <div class="col-2 text-right">
                                <label class="control-label">uploadFile合法域名 :</label>
                            </div>
                            <div class="col-10">
                                <span>https://<?= Yii::$app->request->getHostName() ?></span>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-b-md">
                        <div class="row">
                            <div class="col-2 text-right">
                                <label class="control-label">downloadFile合法域名 :</label>
                            </div>
                            <div class="col-10">
                                <span>https://<?= Yii::$app->request->getHostName() ?></span>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <blockquote>
                        <p>消息推送设置</p>
                    </blockquote>
                    <?= $form->field($model, 'wechat_mini_token')->widget(\common\widgets\input\SecretKeyInput::class, [
                        'number' => 32,
                    ])->hint('必须为英文或者数字，长度为3到32个字符. 请妥善保管, Token 泄露将可能被窃取或篡改平台的操作数据'); ?>
                    <?= $form->field($model, 'wechat_mini_encodingaeskey')->widget(\common\widgets\input\SecretKeyInput::class, [
                        'number' => 43,
                    ])->hint('必须为英文或者数字，长度为43个字符. 请妥善保管, EncodingAESKey 泄露将可能被窃取或篡改平台的操作数据'); ?>
                    <?php $model->wechat_mini_url = Url::toApi(['receive-message/index']) ?>
                    <?= $form->field($model, 'wechat_mini_url')->textInput([
                            'readonly' => true
                    ])->hint('配置地址: 小程序后台 > 开发设置 > 消息推送'); ?>
                </div>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
