<?php

use common\helpers\Url;
use yii\widgets\ActiveForm;
use common\enums\GenderEnum;

/** @var ActiveForm $form */
/** @var \yii\db\ActiveRecord $model */

$form = ActiveForm::begin([
    'validationUrl' => Url::to(['edit', 'id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='row'><div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div></div>",
    ]
]);
?>

<div class="row">
    <div class="col-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'username')->textInput([
                    'disabled' => true
                ]); ?>

                <?= $form->field($model, 'realname')->textInput() ?>
                <?= $form->field($model, 'head_portrait')->widget(\common\widgets\cropper\Cropper::class, []); ?>
                <?= $form->field($model, 'gender')->radioList(GenderEnum::getMap()) ?>
                <?= $form->field($model, 'mobile')->textInput() ?>
                <?= $form->field($model, 'email')->textInput() ?>
                <?= $form->field($model, 'birthday')->widget('kartik\date\DatePicker', [
                    'language' => 'zh-CN',
                    'layout' => '{picker}{input}',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true,// 今日高亮
                        'autoclose' => true,// 选择后自动关闭
                        'todayBtn' => true,// 今日按钮显示
                    ],
                    'options' => [
                        'class' => 'form-control no_bor',
                    ]
                ]); ?>
                <?= \common\widgets\linkage\Linkage::widget([
                    'form' => $form,
                    'model' => $model,
                    // 'template' => 'short',
                ]); ?>
                <?= $form->field($model, 'address')->textarea() ?>
                <?= $form->field($model, 'status')->radioList(\common\enums\StatusEnum::getMap()) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
