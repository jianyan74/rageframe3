<?php

use yii\widgets\ActiveForm;
use common\helpers\Html;

$this->title = '数据迁移生成';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<style>
    label {
        width: 33%;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <?php $form = ActiveForm::begin([]); ?>
            <div class="box-body">
                <div class="col-sm-12">
                    <?= $form->field($model, 'addon')->dropDownList($addonList)->hint('默认在 根目录/console/migrations/, 选择插件则生成的数据迁移文件在 插件/console/migrations/'); ?>
                    <div class="form-group">
                        <label class="control-label" for="migrateform-addon">批量选中</label>
                        <div class="input-group m-b">
                            <input type="text" class="form-control prefix" name="prefix" placeholder="表名前缀" value="">
                            <span class="input-group-btn select"><span class="btn btn-white"> 选中</span></span>
                            <span class="input-group-btn cancel"><span class="btn btn-white"> 全部取消选中</span></span>
                        </div>
                        <div class="help-block"></div>
                    </div>
                    <?= $form->field($model, 'tables')->checkboxList($tableList); ?>
                </div>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">立即创建</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>
    $('.select').click(function () {
        var prefix = $('.prefix').val();
        if (prefix.length > 0) {
            $('#migrateform-tables').find('input').each(function (i, data) {
                if($(data).val().substr(0, prefix.length) == prefix){
                    $(data).prop("checked", true);
                }
            })
        }
    })

    $('.cancel').click(function () {
        $('#migrateform-tables').find('input').each(function (i, data) {
            $(data).prop("checked", false);
        })
    })
</script>
