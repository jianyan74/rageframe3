<?php

use yii\widgets\ActiveForm;
use common\enums\StatusEnum;
use common\helpers\StringHelper;
use common\helpers\Url;
use kartik\datetime\DateTimePicker;
use common\widgets\webuploader\Files;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '砍价', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin([
                'id' => 'form',
                'fieldConfig' => [
                    'template' => "<div class='row'><div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div></div>",
                ],
            ]); ?>
            <div class="box-body">
                <div class="col-lg-12">
                    <?= $form->field($model, 'name')->textInput(); ?>
                    <div class="row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-5">
                            <?= $form->field($model, 'start_time', [
                                'template' => "{label}{input}\n{hint}\n{error}",
                            ])->widget(DateTimePicker::class, [
                                'language' => 'zh-CN',
                                'options' => [
                                    'value' => StringHelper::intToDate($model->start_time),
                                ],
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii',
                                    'todayHighlight' => true,//今日高亮
                                    'autoclose' => true,//选择后自动关闭
                                    'todayBtn' => true,//今日按钮显示
                                ],
                            ])->hint('开播时间需要在当前时间的10分钟后 并且开始时间不能在6个月后'); ?>
                        </div>
                        <div class="col-sm-5">
                            <?= $form->field($model, 'end_time', [
                                'template' => "{label}{input}\n{hint}\n{error}",
                            ])->widget(DateTimePicker::class, [
                                'language' => 'zh-CN',
                                'options' => [
                                    'value' => StringHelper::intToDate($model->end_time),
                                ],
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii',
                                    'todayHighlight' => true,//今日高亮
                                    'autoclose' => true,//选择后自动关闭
                                    'todayBtn' => true,//今日按钮显示
                                ],
                            ])->hint('开播时间和结束时间间隔不得短于30分钟，不得超过24小时'); ?>
                        </div>
                    </div>
                    <?= $form->field($model, 'anchor_name')->textInput(); ?>
                    <?= $form->field($model, 'anchor_wechat')->textInput()->hint('如果未实名认证，需要先前往“<a href="https://res.wx.qq.com/op_res/9rSix1dhHfK4rR049JL0PHJ7TpOvkuZ3mE0z7Ou_Etvjf-w1J_jVX0rZqeStLfwh" target="_blank">小程序直播</a>”小程序进行实名验证'); ?>
                    <?= $form->field($model, 'cover_img')->widget(Files::class, [
                        'config' => [
                            'pick' => [
                                'multiple' => false,
                            ]
                        ]
                    ])->hint('建议600像素 * 1300像素，图片大小不得超过 3M'); ?>
                    <?= $form->field($model, 'share_img')->widget(Files::class, [
                        'config' => [
                            'pick' => [
                                'multiple' => false,
                            ]
                        ]
                    ])->hint('建议像素800*640，大小不超过1M'); ?>
                    <?= $form->field($model, 'feeds_img')->widget(Files::class, [
                        'config' => [
                            'pick' => [
                                'multiple' => false,
                            ]
                        ]
                    ])->hint('建议像素800*800，大小不超过100KB'); ?>
                    <?= $form->field($model, 'live_type')->radioList([1 => '推流', 0 => '手机直播']); ?>
                    <?= $form->field($model, 'is_feeds_public')->radioList(\common\enums\WhetherEnum::getMap()); ?>
                    <?= $form->field($model, 'close_like')->radioList([0 => '开启', 1 => '关闭']); ?>
                    <?= $form->field($model, 'close_goods')->radioList([0 => '开启', 1 => '关闭']); ?>
                    <?= $form->field($model, 'close_share')->radioList([0 => '开启', 1 => '关闭']); ?>
                    <?= $form->field($model, 'close_comment')->radioList([0 => '开启', 1 => '关闭']); ?>
                    <?= $form->field($model, 'close_kf')->radioList([0 => '开启', 1 => '关闭'])->hint('注意需要开启小程序客服'); ?>
                    <?= $form->field($model, 'close_replay')->radioList([0 => '开启', 1 => '关闭']); ?>
                    <?= $form->field($model, 'is_recommend')->radioList(\common\enums\WhetherEnum::getMap()); ?>
                    <?= $form->field($model, 'is_stick')->radioList(\common\enums\WhetherEnum::getMap()); ?>
                    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>
                </div>
            </div>
            <div class="box-footer text-center">
                <span class="btn btn-primary" onclick="beforeSubmit()">保存</span>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>
    function beforeSubmit() {
        // 序列化数据
        var data = $('#form').serializeArray();
        rfAffirm('同步上传中,请不要关闭当前页面');

        $.ajax({
            type: "post",
            url: "<?= Url::to(['edit', 'id' => $model->id]); ?>",
            dataType: "json",
            data: data,
            success: function (data) {
                submitStatus = true;
                if (parseInt(data.code) === 200) {
                    swal("保存成功", "小手一抖就打开了一个框", "success").then((value) => {
                        window.location = "<?= $referrer; ?>";
                    });
                } else {
                    rfMsg(data.message);
                }
            }
        });
    }
</script>
