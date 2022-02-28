<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\widgets\linkage\Linkage;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id'], 'member_id' => $model['member_id']]),
]);
?>
    <div class="modal-header">
        <h4 class="modal-title">基本信息</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'realname')->textInput() ?>
        <?= $form->field($model, 'mobile')->textInput() ?>
        <?= $form->field($model, 'zip_code')->textInput() ?>
        <?= Linkage::widget([
            'form' => $form,
            'model' => $model,
            'template' => 'short',
        ]); ?>
        <?= $form->field($model, 'details')->textarea() ?>
        <?= $form->field($model, 'street_number')->textInput() ?>
        <?= $form->field($model, 'is_default')->checkbox() ?>
        <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>