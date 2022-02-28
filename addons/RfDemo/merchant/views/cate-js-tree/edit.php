<?php

use yii\widgets\ActiveForm;
use common\enums\StatusEnum;

?>

<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'template' => "<div class='row'><div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div></div>",
    ]
]); ?>
<?= $form->field($model, 'pid')->dropDownList($map, [
    'readonly' => true
]) ?>
<?= $form->field($model, 'title')->textInput(); ?>

<?php ActiveForm::end(); ?>
