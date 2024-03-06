<?php

use common\helpers\Html;

?>

<div class="input-group">
    <span class="input-group-prepend">
        <span class="input-group-text"><?= $prepend?></span>
    </span>
    <?= Html::textInput($name, $value, $options); ?>
    <span class="input-group-append">
        <span class="input-group-text"><?= $append?></span>
    </span>
</div>
