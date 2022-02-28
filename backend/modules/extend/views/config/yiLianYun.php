<?= $form->field($dataModel, 'terminal_number')->textInput() ?>
<?= $form->field($dataModel, 'secret_key')->textInput() ?>
<?= $form->field($dataModel, 'app_id')->textInput() ?>
<?= $form->field($dataModel, 'app_secret_key')->textInput() ?>
<?= $form->field($dataModel, 'print_num')->textInput() ?>
<?= $form->field($model, 'extend')->radioList(\common\enums\WhetherEnum::getMap())->label('自动打印') ?>
