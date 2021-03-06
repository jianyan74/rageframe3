<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use common\enums\DevPatternEnum;

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
        <?= $form->field($model, 'title')->textInput() ?>
        <?= $form->field($model, 'name')->textInput() ?>
        <?= $form->field($model, 'icon')->textInput()->hint('详情请参考：<a href="https://fontawesome.com" target="_blank">http://fontawesome.dashgame.com</a>')?>
        <?= $form->field($model, 'sort')->textInput() ?>
        <?= $form->field($model, 'pattern')->checkboxList(DevPatternEnum::getMap())->hint('不选则全部可见') ?>
        <?= $form->field($model, 'is_default_show')->radioList(WhetherEnum::getMap())->hint('默认菜单导航显示') ?>
        <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>

<?php ActiveForm::end(); ?>
