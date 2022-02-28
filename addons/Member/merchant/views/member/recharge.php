<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\helpers\Html;
use common\helpers\ImageHelper;
use addons\Member\merchant\forms\RechargeForm;

?>

<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">充值</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>
    <div class="modal-body">
        <div class="col-12 col-md-12">
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-7">
                        <h2 class="lead"><b><?= $model['id'] ?></b></h2>
                        <p class="text-muted text-sm"><b>用户昵称: </b> <?= empty($model['nickname']) ? '---' : Html::encode($model['nickname']) ?></p>
                        <p class="text-muted text-sm"><b>手机号码: </b> <?= empty($model['mobile']) ? '---' : Html::encode($model['mobile']) ?> </p>
                    </div>
                    <div class="col-5 text-center">
                        <img src="<?= ImageHelper::defaultHeaderPortrait($model->head_portrait) ?>" alt="user-avatar" class="img-circle rf-img-lg">
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#custom-tabs-int">积分</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#custom-tabs-money">余额</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#custom-tabs-growth">成长值</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-int">
                        <?php $form = ActiveForm::begin([
                            'id' => $rechargeForm->formName(),
                            'enableAjaxValidation' => true,
                            'class' => 'form-horizontal',
                            'validationUrl' => Url::to(['recharge', 'id' => $model->id]),
                            'fieldConfig' => [
                                'template' => "<div class='row'><div class='col-sm-2 text-right'>{label}</div><div class='col-sm-9'>{input}\n{hint}\n{error}</div></div>",
                            ]
                        ]); ?>
                        <?= $form->field($rechargeForm, 'old_num')->textInput([
                            'value' => $model['account']['user_integral'],
                            'readonly' => 'readonly'
                        ]) ?>
                        <?= $form->field($rechargeForm, 'change')->radioList(RechargeForm::$changeExplain) ?>
                        <?= $form->field($rechargeForm, 'int')->textInput() ?>
                        <?= $form->field($rechargeForm, 'remark')->textarea() ?>
                        <?= $form->field($rechargeForm,
                            'type')->hiddenInput(['value' => RechargeForm::TYPE_INT])->label(false) ?>
                        <div class="box-footer">
                            <div class="col-sm-12 text-center">
                                <button class="btn btn-primary" type="submit">确认</button>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-money">
                        <?php $form = ActiveForm::begin([
                            'id' => 'money',
                            'enableAjaxValidation' => true,
                            'class' => 'form-horizontal',
                            'validationUrl' => Url::to(['recharge', 'id' => $model->id]),
                            'fieldConfig' => [
                                'template' => "<div class='row'><div class='col-sm-2 text-right'>{label}</div><div class='col-sm-9'>{input}\n{hint}\n{error}</div></div>",
                            ]
                        ]); ?>
                        <?= $form->field($rechargeForm, 'old_num')->textInput([
                            'value' => $model['account']['user_money'],
                            'readonly' => 'readonly'
                        ]) ?>
                        <?= $form->field($rechargeForm, 'change')->radioList(RechargeForm::$changeExplain) ?>
                        <?= $form->field($rechargeForm, 'money')->textInput() ?>
                        <?= $form->field($rechargeForm, 'remark')->textarea() ?>
                        <?= $form->field($rechargeForm, 'type')->hiddenInput(['value' => RechargeForm::TYPE_MONEY])->label(false) ?>
                        <div class="box-footer">
                            <div class="col-sm-12 text-center">
                                <button class="btn btn-primary" type="submit">确认</button>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-growth">
                        <?php $form = ActiveForm::begin([
                            'id' => 'growth',
                            'enableAjaxValidation' => true,
                            'class' => 'form-horizontal',
                            'validationUrl' => Url::to(['recharge', 'id' => $model->id]),
                            'fieldConfig' => [
                                'template' => "<div class='row'><div class='col-sm-2 text-right'>{label}</div><div class='col-sm-9'>{input}\n{hint}\n{error}</div></div>",
                            ]
                        ]); ?>
                        <?= $form->field($rechargeForm, 'old_num')->textInput([
                            'value' => $model['account']['user_growth'],
                            'readonly' => 'readonly'
                        ]) ?>
                        <?= $form->field($rechargeForm, 'change')->radioList(RechargeForm::$changeExplain) ?>
                        <?= $form->field($rechargeForm, 'growth')->textInput() ?>
                        <?= $form->field($rechargeForm, 'remark')->textarea() ?>
                        <?= $form->field($rechargeForm, 'type')->hiddenInput(['value' => RechargeForm::TYPE_GROWTH])->label(false) ?>
                        <div class="box-footer">
                            <div class="col-sm-12 text-center">
                                <button class="btn btn-primary" type="submit">确认</button>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
