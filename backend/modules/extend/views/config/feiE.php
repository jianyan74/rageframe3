<?= $form->field($dataModel, 'user')->textInput() ?>
<?= $form->field($dataModel, 'ukey')->textInput() ?>
<?= $form->field($dataModel, 'sn')->textInput() ?>
<?= $form->field($dataModel, 'print_num')->textInput() ?>
<?= $form->field($model, 'extend')->radioList(\common\enums\WhetherEnum::getMap())->label('自动打印') ?>
