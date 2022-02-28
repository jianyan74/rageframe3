<?php

use common\helpers\Url;

$this->title = '数据统计';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['statistics']];

?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['index']) ?>"> 全局日志</a></li>
                <li class="active"><a href="<?= Url::to(['statistics']) ?>"> 数据统计</a></li>
                <li><a href="<?= Url::to(['ip-statistics']) ?>"> IP 统计</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">流量状态分析</h3>
                        </div>
                        <div class="box-body">
                            <?= \common\widgets\echarts\Echarts::widget([
                                'config' => [
                                    'server' => Url::to(['flow-stat']),
                                    'height' => '400px',
                                ]
                            ]) ?>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">异常状态分析</h3>
                        </div>
                        <div class="box-body">
                            <?= \common\widgets\echarts\Echarts::widget([
                                'config' => [
                                    'server' => Url::to(['stat']),
                                    'height' => '400px',
                                ]
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
