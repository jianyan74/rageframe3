<?php

use common\helpers\Url;
use common\enums\NotifyTypeEnum;

?>

<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge rf-notify-all-count"><?= $count; ?></span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header"><span class="rf-notify-all-count"><?= $count; ?></span> 消息提醒</span>
        <div class="dropdown-divider"></div>
        <a href="<?= Url::to(['/notify/remind', 'type' => NotifyTypeEnum::ANNOUNCE]); ?>" class="dropdown-item J_menuItem" data-title="公告消息">
            <i class="fas fa-comments mr-2"></i> 收到 <span class="announce-count"><?= $notify[NotifyTypeEnum::ANNOUNCE]['count'] ?? 0; ?></span> 条公告消息
            <span class="float-right text-muted text-sm announce-time">
                <?=
                isset($notify[NotifyTypeEnum::ANNOUNCE])
                    ? Yii::$app->formatter->asRelativeTime($notify[NotifyTypeEnum::ANNOUNCE]['created_at'])
                    : '';
                ?>
            </span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="<?= Url::to(['/notify/remind', 'type' => NotifyTypeEnum::REMIND]); ?>" class="dropdown-item J_menuItem" data-title="提醒消息">
            <i class="fas fa-bell mr-2"></i> 收到 <span class="remind-count"><?= $notify[NotifyTypeEnum::REMIND]['count'] ?? 0; ?></span> 条提醒消息
            <span class="float-right text-muted text-sm remind-time">
                <?=
                isset($notify[NotifyTypeEnum::REMIND])
                    ? Yii::$app->formatter->asRelativeTime($notify[NotifyTypeEnum::REMIND]['created_at'])
                    : '';
                ?>
            </span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="<?= Url::to(['/notify/remind']); ?>" class="dropdown-item dropdown-footer J_menuItem" onclick="$('body').click();">全部消息</a>
    </div>
</li>