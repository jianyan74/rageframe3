<?php

use common\helpers\Html;
use common\enums\StatusEnum;
use common\widgets\input\SecretKeyInput;

?>

<div class="form-group">
    <?= Html::label($row['title'], $row['name'], ['class' => 'control-label demo']); ?>
    <?php if ($row['is_hide_remark'] != StatusEnum::ENABLED) { ?>
        <small><?= \yii\helpers\HtmlPurifier::process($row['remark']) ?></small>
    <?php } ?>
    <?= SecretKeyInput::widget([
        'name' => 'config[' . $row['name'] . ']',
        'value' => $row['value']['data'] ?? $row['default_value'],
        'options' => [
            'id' => $row['id'],
            'class' => 'form-control',
        ],
        'number' => $row['extra'],
    ])?>
</div>
