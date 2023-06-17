<?php

use common\helpers\Html;

?>

<div class="input-group">
    <?= Html::textInput($name, $value, $options); ?>
    <span class="input-group-append">
        <span class="input-group-text" onclick="createKey(<?= $number ?>, '<?= $options['id'] ?>')">生成新的</span>
    </span>
</div>
