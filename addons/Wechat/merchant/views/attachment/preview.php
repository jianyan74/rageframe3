<?php

use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'fieldConfig' => [
        'template' => "<div class='row'><div class='col-sm-3 text-right'>{label}</div><div class='col-sm-9'>{input}\n{hint}\n{error}</div></div>",
    ]
]);

?>

<div class="modal-header">
    <h4 class="modal-title">手机预览</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <?= $form->field($model, 'type')->radioList(['1' => '微信号','2' => '粉丝标识(openId)']) ?>
    <?= $form->field($model, 'content')->textInput() ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>

<?php ActiveForm::end(); ?>
