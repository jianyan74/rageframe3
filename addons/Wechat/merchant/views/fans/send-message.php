<?php

use common\helpers\Url;
use common\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\ImageHelper;
use addons\Wechat\merchant\widgets\selector\Select;

$form = ActiveForm::begin([
    'id' => 'sendMessage'
]);

?>

<div class="modal-header">
    <h4 class="modal-title">发送消息</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>

<div class="modal-body">
    <div class="col-md-12">
        <table class="table text-center">
            <tbody>
            <tr>
                <td rowspan="2">
                    <?= Html::img(ImageHelper::defaultHeaderPortrait(Html::encode($model->auth->head_portrait ?? '')),
                        [
                            'class' => 'img-circle rf-img-md elevation-1',
                        ])?>
                </td>
                <td><?= Html::encode($model->auth->nickname ?? '') ?></td>
            </tr>
            <tr>
                <td><?= Html::encode($model['openid']) ?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link text active" data-toggle="pill" href="#custom-1" onclick="setType('text')">内容</a></li>
                        <li class="nav-item"><a class="nav-link image" data-toggle="pill" href="#custom-2" onclick="setType('image')">图片</a></li>
                        <li class="nav-item"><a class="nav-link news" data-toggle="pill" href="#custom-3" onclick="setType('news')">图文</a></li>
                        <li class="nav-item"><a class="nav-link video" data-toggle="pill" href="#custom-4" onclick="setType('video')">视频</a></li>
                        <li class="nav-item"><a class="nav-link voice" data-toggle="pill" href="#custom-5" onclick="setType('voice')">语音</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="custom-1">
                            <?= Html::textarea('content', '', [
                                'class' => 'form-control',
                                'id' => 'text',
                            ]) ?>
                        </div>
                        <div class="tab-pane fade" id="custom-2">
                            <?= Select::widget([
                                'name' => 'image',
                                'type' => 'image',
                            ]) ?>
                        </div>
                        <div class="tab-pane fade" id="custom-3">
                            <?= Select::widget([
                                'name' => 'news',
                                'type' => 'news',
                            ]) ?>
                        </div>
                        <div class="tab-pane fade" id="custom-4">
                            <?= Select::widget([
                                'name' => 'video',
                                'type' => 'video',
                            ]) ?>
                        </div>
                        <div class="tab-pane fade" id="custom-5">
                            <?= Select::widget([
                                'name' => 'voice',
                                'type' => 'voice',
                            ]) ?>
                        </div>
                        <div class="col-sm-12">注意：三天内有互动的才可发送消息</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= Html::hiddenInput('type', 'text', ['id' => 'type']) ?>

<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <span class="btn btn-primary" onclick="beforeSubmit()">发送消息</span>
</div>
<?php ActiveForm::end(); ?>

<script>
    // 设置类型
    function setType(type) {
        $('#type').val(type)
    }

    function beforeSubmit() {
        var val = description = title = '';
        var id = "<?= $model['id']; ?>";
        var type = $('#type').val();

        if (type == 'text' && !$('#text').val()) {
            rfWarning('请填写内容');
            return false;
        }

        if (type == 'image' && !$("input[name='image']").val()) {
            rfWarning('请选择图片');
            return false;
        }

        if (type == 'news' && !$("input[name='news']").val()) {
            rfWarning('请选择图文');
            return false;
        }

        if (type == 'video' && !$("input[name='video']").val()) {
            rfWarning('请选择视频');
            return false;
        }

        if (type == 'voice' && !$("input[name='voice']").val()) {
            rfWarning('请选择语音');
            return false;
        }

        $.ajax({
            type: "post",
            url: "<?= Url::to(['fans/send-message', 'openid' => $model->openid])?>",
            dataType: "json",
            data: $("#sendMessage").serialize(),
            success: function (data) {
                if (data.code == 200) {
                    $('.close').click();
                    rfAffirm('发送成功');
                } else {
                    rfWarning(data.message);
                }
            }
        });
    }
</script>
