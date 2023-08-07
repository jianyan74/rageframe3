<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\widgets\webuploader\Files;
use common\enums\WhetherEnum;

$this->title = '微信配置';
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
                        <p>微信公众号设置</p>
                    </blockquote>
                    <?= $form->field($model, 'wechat_mp_account')->textInput()->hint('填写公众号的账号，一般为英文账号'); ?>
                    <?= $form->field($model, 'wechat_mp_id')->textInput()->hint('在给粉丝发送客服消息时,原始ID不能为空。建议您完善该选项'); ?>
                    <?= $form->field($model, 'wechat_mp_qrcode')->widget(Files::class, [
                        'type' => 'images',
                        'theme' => 'default',
                        'themeConfig' => [],
                        'config' => [
                            'pick' => [
                                'multiple' => false,
                            ],
                        ]
                    ]); ?>
                    <blockquote>
                        <p>开发设置</p>
                    </blockquote>
                    <?= $form->field($model, 'wechat_mp_app_id')->textInput(); ?>
                    <?= $form->field($model, 'wechat_mp_appsecret')->textInput()->hint('AppID和AppSecret来自于您申请开发接口时提供的账号和密码，且公众号为已认证服务号'); ?>
                    <blockquote>
                        <p>消息推送设置</p>
                    </blockquote>
                    <?= $form->field($model, 'wechat_mp_token')->widget(\common\widgets\input\SecretKeyInput::class, [
                        'number' => 32,
                    ])->hint('必须为英文或者数字，长度为3到32个字符. 请妥善保管, Token 泄露将可能被窃取或篡改平台的操作数据'); ?>
                    <?= $form->field($model, 'wechat_mp_encodingaeskey')->widget(\common\widgets\input\SecretKeyInput::class, [
                        'number' => 43,
                    ])->hint('必须为英文或者数字，长度为43个字符. 请妥善保管, EncodingAESKey 泄露将可能被窃取或篡改平台的操作数据'); ?>
                    <?php $model->wechat_mp_url = Url::toApi(['receive-message/index']) ?>
                    <?= $form->field($model, 'wechat_mp_url')->textInput([
                        'readonly' => true
                    ])->hint('配置地址: 小程序后台 > 开发设置 > 消息推送'); ?>
                    <blockquote>
                        <p>消息存储配置</p>
                    </blockquote>
                    <?= $form->field($historyForm, 'history_status')->radioList(WhetherEnum::getOpenMap())->hint('开启此项后，系统将记录用户与系统的往来消息记录。') ?>
                    <?= $form->field($historyForm, 'history_message_date')->textInput()->hint('设置保留历史消息记录的天数，为0则为保留全部，需要开启定时任务。') ?>
                    <?= $form->field($historyForm, 'history_utilization_status')->radioList(WhetherEnum::getOpenMap())->hint('开启此项后，系统将记录系统中的规则的使用情况，并生成走势图。') ?>
                </div>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
