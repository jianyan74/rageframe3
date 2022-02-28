<?php

use common\helpers\Html;
use kartik\daterange\DateRangePicker;

$addon = <<< HTML
<div class="input-group-append">
    <span class="input-group-text">
        <i class="fas fa-calendar-alt"></i>
    </span>
</div>
HTML;

?>

<style>
    .echarts-nav span {
        margin-right: 5px;
    }

    .pull-left {
        float: left !important;
    }
</style>

<div class="box-body" id="<?= $boxId; ?>">
    <div class="m-b-xl echarts-nav col-12">
        <?php $i = 0; ?>
        <?php foreach ($themeConfig as $key => $value) { ?>
            <?php if ($key == 'customData') { ?>
                <span class="hide" data-type="customData" data-start=""  data-end="" id="freedom-<?= $boxId; ?>">自定义日期</span>
                <div class="input-group drp-container col-4 pull-left" style="margin-top: -5px;margin-left: 10px;width:240px">
                    <?= DateRangePicker::widget([
                        'id' => 'queryDate-' . $boxId,
                        'name' => 'queryDate-' . $boxId,
                        'value' => '',
                        'useWithAddon' => true,
                        'convertFormat' => true,
                        'startAttribute' => 'start_time',
                        'endAttribute' => 'end_time',
                        'pluginEvents' => [
                            "apply.daterangepicker" => "function(ev, picker) { 
                            var startDate = picker.startDate.format('YYYY-MM-DD');
                            var endDate = picker.endDate.format('YYYY-MM-DD');
                            var boxID = '{$boxId}';
                     
                            $('#freedom-' + boxID).attr('data-start', startDate);
                            $('#freedom-' + boxID).attr('data-end', endDate);
                            
                            // 触发点击
                            $('#freedom-' + boxID).trigger('click');
                    }",
                        ],
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => '开始时间 - 结束时间'
                        ],
                        'pluginOptions' => [
                            'locale' => ['format' => 'Y-m-d'],
                        ],
                    ]) . $addon;?>
                </div>
            <?php } else { ?>
                <span class="<?= $i == 0 ? 'orange' : '' ?> pointer pull-left" data-type="<?= Html::encode($key) ?>"> <?= Html::encode($value) ?></span>
            <?php } ?>
            <?php $i++; ?>
        <?php } ?>
    </div>
    <div style="height: <?= $config['height'] ?>" id="<?= $boxId; ?>-echarts"></div>
    <!-- /.row -->
</div>