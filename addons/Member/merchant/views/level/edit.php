<?php

use yii\widgets\ActiveForm;
use common\widgets\webuploader\Files;
use common\helpers\ArrayHelper;

$this->title = '编辑';
$this->params['breadcrumbs'][] = ['label' => '等级信息', 'url' => ['index']];
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
                    'template' => "<div class='row'><div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div></div>",
                ]
            ]); ?>
            <div class="box-body">
                <?php if($model->isNewRecord || ($model->level != 1 && !$model->isNewRecord)) {?>
                    <?= $form->field($model, 'level')->widget(\kartik\select2\Select2::class, [
                        'data' => ArrayHelper::numBetween(1, 99),
                        'options' => ['placeholder' => '请选择'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->hint('数字越大等级越高'); ?>
                <?php } ?>
                <?= $form->field($model, 'name')->textInput() ?>
                <?= $form->field($model, 'discount')->textInput()->hint('折扣范围：0 - 10 折，10 折为不打折') ?>
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-3">
                        <?= $form->field($model, 'integral', [
                            'template' => "{label}{input}\n{hint}\n{error}",
                        ])->textInput()->hint('根据等级配置启用') ?>
                    </div>
                    <div class="col-3">
                        <?= $form->field($model, 'money', [
                            'template' => "{label}{input}\n{hint}\n{error}",
                        ])->textInput()->hint('根据等级配置启用') ?>
                    </div>
                    <div class="col-3">
                        <?= $form->field($model, 'growth', [
                            'template' => "{label}{input}\n{hint}\n{error}",
                        ])->textInput()->hint('根据等级配置启用') ?>
                    </div>
                </div>
                <?= $form->field($model, 'icon')->widget(Files::class, [
                    'config' => [
                        'pick' => [
                            'multiple' => false,
                        ],
                    ]
                ]); ?>
                <?= $form->field($model, 'cover')->widget(Files::class, [
                    'config' => [
                        'pick' => [
                            'multiple' => false,
                        ],
                    ]
                ]); ?>
                <?= $form->field($model, 'detail')->textarea() ?>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
