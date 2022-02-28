<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use common\enums\DevPatternEnum;
use unclead\multipleinput\MultipleInput;

$this->title = '编辑';

$form = ActiveForm::begin([
    'enableAjaxValidation' => false,
    'validationUrl' => Url::to(['edit', 'id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='row'><div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div></div>",
    ],
]);
?>

<div class="col-12 pt-3">
    <div class="box">
        <div class="box-body">
            <?= $form->field($model, 'pid')->dropDownList($menuDropDownList) ?>
            <?= $form->field($model, 'title')->textInput() ?>
            <?= $form->field($model, 'name')->textInput() ?>
            <?= $form->field($model, 'url')->textInput()->hint("例如：/index/index，要绝对路由哦") ?>
            <?= $form->field($model, 'params')->widget(MultipleInput::class, [
                'iconSource' => 'fa',
                'max' => 10,
                'columns' => [
                    [
                        'name' => 'key',
                        'title' => '参数名',
                        'enableError' => false,
                        'options' => [
                            'class' => 'input-priority',
                        ],
                    ],
                    [
                        'name' => 'value',
                        'title' => '参数值',
                        'enableError' => false,
                        'options' => [
                            'class' => 'input-priority',
                        ],
                    ],
                ],
            ])->label(false);
            ?>
            <?= $form->field($model, 'icon')->textInput()->hint('详情请参考：<a href="https://fontawesome.com" target="_blank">http://fontawesome.dashgame.com</a>') ?>
            <?= $form->field($model, 'sort')->textInput() ?>
            <?= $form->field($model, 'pattern')->checkboxList(DevPatternEnum::getMap())->hint('不选则全部可见') ?>
            <?= $form->field($model, 'dev')->radioList(WhetherEnum::getMap())->hint('去 网站设置->系统设置 里面开启或关闭开发模式,开启后才可显示该菜单') ?>
            <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
