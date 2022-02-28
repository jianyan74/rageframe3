<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\helpers\Html;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['create']),
    'fieldConfig' => [
        'template' => "<div class='row'><div class='col-sm-3 text-right'>{label}</div><div class='col-sm-9'>{input}\n{hint}\n{error}</div></div>",
    ]
]);
?>

<div class="modal-header">
    <h4 class="modal-title">基本信息</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <?= $form->field($model, 'username')->textInput()->hint('创建以后无法修改，请确认后认真填写'); ?>
    <?= $form->field($model, 'password')->passwordInput(); ?>
    <?= $form->field($model, 'role_ids')->widget(\kartik\select2\Select2::class, [
        'data' => $model->roles,
        'options' => [
            'placeholder' => '选择授权角色',
            'multiple' => true
        ],
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators' => [',', ' '],
            'maximumInputLength' => 20
        ],
    ]);?>
    <?= Html::modelBaseCss(); ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>

<?php ActiveForm::end(); ?>
