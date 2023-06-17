<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\MemberLevelUpgradeTypeEnum;
use common\enums\MemberLevelAutoUpgradeTypeEnum;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['level/index']) ?>"> 会员等级</a></li>
                <li class="active"><a href="<?= Url::to(['level-config/edit']) ?>"> 等级配置</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
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
    </div>
</div>
