<?php

use common\helpers\Url;
use common\enums\NotifyTypeEnum;

list($notify, $count) = Yii::$app->services->notifyMember->getNotReadNotify(Yii::$app->user->identity->merchant_id);

?>

<ul class="nav nav-pills nav-stacked">
    <li class="nav-item">
        <a href="<?= Url::to(['remind']); ?>" class="nav-link"><i class="fa fa-dot-circle"></i> 全部消息</a>
        <a href="<?= Url::to(['remind', 'type' => NotifyTypeEnum::ANNOUNCE]); ?>" title="公告列表" class="nav-link"><i class="fa fa-comments"></i> 公告列表</a>
        <a href="<?= Url::to(['remind', 'type' => NotifyTypeEnum::REMIND]); ?>" title="提醒列表" class="nav-link"><i class="fa fa-bell"></i> 提醒列表</a>
    </li>
</ul>

<script>
    $(document).ready(function () {
        var allCount = $('.rf-notify-all-count', window.parent.document);
        $(allCount).text(<?= $count; ?>);

        // 公告
        var announceCount = $('.announce-count', window.parent.document);
        $(announceCount).text(<?= $notify[NotifyTypeEnum::ANNOUNCE]['count'] ?? 0; ?>);
        if (parseInt($(announceCount).text()) === 0) {
            $('.announce-time', window.parent.document).text('');
        }

        // 提醒
        var remindCount = $('.remind-count', window.parent.document);
        $(remindCount).text(<?= $notify[NotifyTypeEnum::REMIND]['count'] ?? 0; ?>);
        if (parseInt($(remindCount).text()) === 0) {
            $('.remind-time', window.parent.document).text('');
        }
    })
</script>