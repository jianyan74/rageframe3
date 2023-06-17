<?php

use yii\widgets\ActiveForm;
use common\enums\GenderEnum;
use common\enums\StatusEnum;
use kartik\select2\Select2;
use common\enums\AccessTokenGroupEnum;

$this->title = '编辑';
$this->params['breadcrumbs'][] = ['label' => '会员信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "<div class='row'><div class='col-1 text-right'>{label}</div><div class='col-11'>{input}\n{hint}\n{error}</div></div>",
                ]
            ]); ?>
            <div class="box-body">
                <?= $form->field($model, 'username')->textInput([
                    'readonly' => true
                ]) ?>
                <?= $form->field($model, 'realname')->textInput() ?>
                <?= $form->field($model, 'nickname')->textInput() ?>
                <?= $form->field($model, 'mobile')->textInput() ?>
                <?= $form->field($model, 'gender')->radioList(GenderEnum::getMap()) ?>
                <?= $form->field($model, 'head_portrait')->widget(\common\widgets\cropper\Cropper::class, []); ?>
                <?= $form->field($model, 'qq')->textInput() ?>
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
                <?= $form->field($model, 'tags')->widget(Select2::class, [
                    'data' => Yii::$app->services->memberTag->getMap(),
                    'options' => [
                        'placeholder' => '请选择标签',
                        'multiple' => true
                    ],
                    'maintainOrder' => true,
                    'pluginOptions' => [
                        'tags' => true,
                        'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 20
                    ],
                ]); ?>
                <?= $form->field($model, 'source')->dropDownList(AccessTokenGroupEnum::getMap()) ?>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
