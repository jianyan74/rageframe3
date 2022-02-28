<?php

use yii\widgets\ActiveForm;

$this->title = '清理缓存';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">缓存</h3>
            </div>
            <?php $form = ActiveForm::begin([]); ?>
            <div class="box-body text-center">
                <?= $form->field($model, 'cache')->checkbox() ?>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-primary" type="submit">立即清理</button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
