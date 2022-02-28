<?php

use yii\widgets\ActiveForm;
use common\enums\StatusEnum;

?>

<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'template' => "<div class='row'><div class='col-sm-1 text-right'>{label}</div><div class='col-sm-11'>{input}\n{hint}\n{error}</div></div>",
    ]
]); ?>
<?= $form->field($model, 'pid')->dropDownList($map, [
    'readonly' => true
]) ?>
<?= $form->field($model, 'id')->textInput([
    'readonly' => !empty($model->id)
])->hint('创建后不可修改'); ?>
<?= $form->field($model, 'title')->textInput(); ?>
<?= $form->field($model, 'short_title')->textInput(); ?>
<?= $form->field($model, 'area_code')->textInput(); ?>
<?= $form->field($model, 'zip_code')->textInput(); ?>
<?= $form->field($model, 'pinyin')->textInput(); ?>
<?= $form->field($model, 'lng')->textInput(); ?>
<?= $form->field($model, 'lat')->textInput(); ?>
<?= $form->field($model, 'sort')->textInput(); ?>
<?php ActiveForm::end(); ?>