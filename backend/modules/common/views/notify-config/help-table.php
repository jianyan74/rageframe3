<?php

use common\helpers\Html;

?>

<div class="modal-header">
    <h4 class="modal-title"><?= $nameMap[$name]; ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <?php if(!empty($data)) {?>
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <?php foreach ($data as $key => $datum) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $key === 0 ? 'active' : ''; ?>" data-toggle="pill" href="#custom-<?= $datum['prefix']; ?>"><?= Html::encode($datum['title'])?></a>
                        </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#custom-rf-demo">使用示例</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <?php foreach ($data as $key => $datum) { ?>
                        <div class="tab-pane fade <?= $key === 0 ? 'active show' : ''; ?>" id="custom-<?= $datum['prefix']; ?>">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>参数值</th>
                                    <th>说明</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($datum['fields'] as $value) { ?>
                                    <tr>
                                        <td><?= Html::encode($value['name']) ?></td>
                                        <td><?= Html::encode($value['comment']) ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                    <div class="tab-pane fade" id="custom-rf-demo">
                        <blockquote>
                            <p>申请到的模板/订阅消息</p>
                        </blockquote>
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <td>模板ID</td>
                                <td>MjqHEpEikY1J6ay97OBpxmgQlNQcfhH--8gY</td>
                            </tr>
                            <tr>
                                <td>详细内容</td>
                                <td>
                                    <span>订单号 {{character_string2.DATA}}</span> <br>
                                    <span>订单金额 {{amount7.DATA}}</span> <br>
                                    <span>下单时间 {{time11.DATA}}</span> <br>
                                    <span>收货人 {{thing8.DATA}}</span> <br>
                                    <span>收货地址 {{thing9.DATA}}</span> <br>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <blockquote>
                            <p>使用案例</p>
                        </blockquote>
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <th>参数名</th>
                                <th>参数值</th>
                            </tr>
                            <tr>
                                <td>character_string2</td>
                                <td>{order.order_sn}</td>
                            </tr>
                            <tr>
                                <td>amount7</td>
                                <td>{order.pay_money}</td>
                            </tr>
                            <tr>
                                <td>time11</td>
                                <td>{order.pay_time}</td>
                            </tr>
                            <tr>
                                <td>thing8</td>
                                <td>{order.receiver_name}</td>
                            </tr>
                            <tr>
                                <td>thing9</td>
                                <td>{order.receiver_details}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    <?php } else { ?>
        <div class="text-center pt-5 pb-5 help">
            暂无提醒
        </div>
    <?php } ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>
