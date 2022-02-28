<?php

use common\helpers\Url;
use common\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;

$addon = <<< HTML
<div class="input-group-append">
    <span class="input-group-text">
        <i class="fas fa-calendar-alt"></i>
    </span>
</div>
HTML;

$this->title = '粉丝关注统计';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<?= Html::jsFile('@web/resources/plugins/echarts/echarts-all.js') ?>

<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="ion ion-stats-bars green"></i> <?= $today['new_attention']; ?></span>
                <span class="info-box-text">今日新关注(人)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-arrow-graph-down-right red"></i> <?= $today['cancel_attention']; ?></span>
                <span class="info-box-text">今日取消关注(人)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-arrow-graph-up-right green"></i> <?= $today['increase_attention']; ?></span>
                <span class="info-box-text">今日净增关注(人)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><?= $countFollow; ?></span>
                <span class="info-box-text">累积关注(人)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>

<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="ion ion-stats-bars green"></i> <?= $yesterday['new_attention']; ?></span>
                <span class="info-box-text">昨日新关注(人)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-arrow-graph-down-right red"></i> <?= $yesterday['cancel_attention']; ?></span>
                <span class="info-box-text">昨日取消关注(人)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-arrow-graph-up-right green"></i> <?= $yesterday['increase_attention']; ?></span>
                <span class="info-box-text">昨日净增关注(人)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><?= $yesterday['cumulate_attention']; ?></span>
                <span class="info-box-text">昨日累积关注(人)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>

<div class="row">
    <div class="col-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
            </div>
            <div class="box-body table-responsive">
                <?= \common\widgets\echarts\Echarts::widget([
                    'config' => [
                        'server' => Url::to(['fans-stat']),
                    ],
                    'themeConfig' => [
                        'this7Day' => '近7天',
                        'this30Day' => '近30天',
                        'customData' => '自定义区间'
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>
