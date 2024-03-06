<?php

use common\helpers\Html;

?>

<div class="input-group">
    <?= Html::textInput($name, $value, $options); ?>
    <span class="input-group-append">
        <span class="input-group-text pointer copy-link" data-clipboard-action="copy" data-clipboard-target="#<?= $options['id'] ?>"><?= $title?></span>
    </span>
</div>

<script>
    $(document).ready(function () {
        var clipboard = new ClipboardJS('.copy-link');

        clipboard.on('success', function(e) {
            console.info('Action:', e.action);
            console.info('Text:', e.text);
            console.info('Trigger:', e.trigger);

            e.clearSelection();
            rfMsg('复制成功');
        });

        clipboard.on('error', function(e) {
            console.error('Action:', e.action);
            console.error('Trigger:', e.trigger);
            rfMsg(e.trigger)
        });
    });
</script>
