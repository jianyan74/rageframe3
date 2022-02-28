<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use unclead\multipleinput\MultipleInput;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'name' => $model['name'], 'type' => $model['type']]),
    'fieldConfig' => [
        'template' => "<div class='row'><div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div></div>",
    ],
]);

?>

    <div class="modal-header">
        <h4 class="modal-title"><?= $nameMap[$model['name']] . ' - ' . $typeMap[$model['type']]; ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'template_id')->textInput()->hint('注意：请自行去小程序后台申请微信模板消息ID'); ?>
        <?= $form->field($model, 'content')->widget(MultipleInput::class, [
            'iconSource' => 'fa',
            'max' => 10,
            'columns' => [
                [
                    'name'  => 'key',
                    'title' => '参数名',
                    'enableError' => false,
                    'options' => [
                        'class' => 'input-priority'
                    ]
                ],
                [
                    'name'  => 'value',
                    'title' => '参数值',
                    'enableError' => false,
                    'options' => [
                        'class' => 'input-priority'
                    ]
                ],
                [
                    'name'  => 'color',
                    'title' => '颜色(默认:#000000)',
                    'enableError' => false,
                    'options' => [
                        'class' => 'input-priority',
                    ]
                ],
            ]
        ])->label(false);
        ?>
        <?= $form->field($model, 'url')->textInput()->hint('例如：index?foo=bar'); ?>
        <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>