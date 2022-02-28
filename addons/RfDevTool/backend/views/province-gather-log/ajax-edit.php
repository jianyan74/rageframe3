<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\helpers\Html;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => false,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
]);
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span>
        </button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label">请求前缀</label>
            <?= Html::textInput('chlidPrefix', $model->data['chlidPrefix'], [
                'class' => 'form-control'
            ])?>
            <div class="help-block"></div>
        </div>
        <div class="form-group">
            <label class="control-label">请求链接</label>
            <?= Html::textInput('chlidLink', $model->data['chlidLink'], [
                'class' => 'form-control'
            ])?>
            <div class="help-block"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>