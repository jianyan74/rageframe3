<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;

$disabled = $model->name === 'Authority';

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
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
        <?= $form->field($model, 'title')->textInput(['disabled' => $disabled]) ?>
        <?= $form->field($model, 'author')->textInput(['disabled' => $disabled]) ?>
        <?= $form->field($model, 'version')->textInput()->hint('如果不了解其更新机制，请不要随意修改版本号') ?>
        <?= $form->field($model, 'brief_introduction')->textInput(['disabled' => $disabled]) ?>
        <?= $form->field($model, 'description')->textarea(['disabled' => $disabled]) ?>
        <?php if ($disabled == false) { ?>
            <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
        <?php } ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>
