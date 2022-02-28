<?php

use yii\widgets\ActiveForm;
use common\enums\MemberLevelUpgradeTypeEnum;
use common\enums\MemberLevelAutoUpgradeTypeEnum;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">等级配置</h3>
            </div>
            <?php $form = ActiveForm::begin([]); ?>
            <div class="box-body">
                <div class="col-lg-12">
                    <?= $form->field($model, 'upgrade_type')->radioList(MemberLevelUpgradeTypeEnum::getMap()); ?>
                    <?= $form->field($model, 'auto_upgrade_type')
                        ->radioList(MemberLevelAutoUpgradeTypeEnum::getMap())
                        ->hint('设置会员自动升级后，必须成为会员才可以自动升级等级');
                    ?>
                </div>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
