<?php

use yii\helpers\Json;
use common\helpers\Html;
use common\enums\StatusEnum;
use unclead\multipleinput\MultipleInput;

$value = isset($row['value']['data']) ? Json::decode($row['value']['data']) : [];

$columns = [];
$count = count($option);
foreach ($option as $key => $v) {
    $columns[] = [
        'name' => $key,
        'title' => $v,
        'enableError' => false,
        'options' => [
            'class' => 'input-priority'
        ]
    ];

    $count == 1 && $value = $value[$key] ?? [];
}
?>

<div class="form-group">
    <?= Html::label($row['title'], $row['name'], ['class' => 'control-label demo']); ?>
    <?php if ($row['is_hide_remark'] != StatusEnum::ENABLED) { ?>
        <small><?= \yii\helpers\HtmlPurifier::process($row['remark']) ?></small>
    <?php } ?>
    <div class="col-sm-push-10">
        <?= MultipleInput::widget([
            'max' => 50,
            'name' => "config[" . $row['name'] . "]",
            'value' => $value,
            'columns' => $columns,
            'iconSource' => MultipleInput::ICONS_SOURCE_FONTAWESOME
        ]) ?>
    </div>
</div>
