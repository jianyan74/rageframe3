<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;

/** @var \common\forms\ExportForm $model */
$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['export']),
]);
?>

<div class="modal-header">
    <h4 class="modal-title">基本信息</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <?= $form->field($model, 'info')->checkboxList($model->getInfo()); ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">根据筛选查询批量导出</button>
</div>

<?php ActiveForm::end(); ?>
