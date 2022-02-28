<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'name' => $model['name'], 'type' => $model['type']]),
    'fieldConfig' => [
        'template' => "<div class='row'><div class='col-sm-1 text-right'>{label}</div><div class='col-sm-11'>{input}\n{hint}\n{error}</div></div>",
    ],
]);

?>

<div class="modal-header">
    <h4 class="modal-title"><?= $nameMap[$model['name']] . ' - ' . $typeMap[$model['type']]; ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <?= $form->field($model, 'title')->textInput(); ?>
    <?= $form->field($model, 'content')->textarea(); ?>
    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
