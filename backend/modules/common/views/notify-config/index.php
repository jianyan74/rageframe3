<?php

use common\helpers\Url;
use common\enums\StatusEnum;

$this->title = '消息配置';

?>

<div class="row">
    <?php foreach ($data as $datum) { ?>
        <div class="col-3">
            <div class="box box-solid">
                <div class="box-header">
                    <i class="fa fa-circle rf-circle" style="font-size: 8px"></i>
                    <h3 class="box-title"><?= $datum['value'] ?></h3>
                    <a href="<?= Url::to(['help-table', 'name' => $datum['name']])?>"
                       data-toggle="modal"
                       data-target="#ajaxModalLg">
                        <i class="iconfont iconbaocuo help float-right" title="辅助说明"></i>
                    </a>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <?php foreach ($datum['params'] as $key => $param) { ?>
                            <a href="<?= Url::to(['ajax-edit', 'name' => $datum['name'], 'type' => $key]) ?>"
                               class="pull-left knob-label"
                               data-toggle="modal"
                               data-target="#ajaxModalLg"
                               style="padding-right: 10px;padding-left: 10px;">
                                <i class="icon <?= $param['status'] == StatusEnum::ENABLED ? 'ion-android-done' : 'ion-android-close'; ?>"></i>
                                <?= $typeMap[$key] ?? '' ?>
                            </a>
                        <?php } ?>
                        <!-- ./col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    <?php } ?>
</div>
