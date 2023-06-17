<?php

use common\helpers\Url;
use common\helpers\Auth;

$this->title = '数据备份';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?= Url::to(['backups']) ?>"> 数据表列表</a></li>
                <li class="hide"><a href="<?= Url::to(['restore']) ?>"> 数据还原</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <div class="col-sm-12 normalPaddingJustV">
                        <div class="btn-group m-l-n-sm">
                            <!-- 权限校验 -->
                            <?php if (Auth::verify('/rf-dev-tool/data-base/export')) { ?>
                                <a class="btn btn-white table-list-database hide" href="javascript:void(0);" data-type="1">立即备份</a>
                            <?php } ?>
                            <!-- 权限校验 -->
                            <?php if (Auth::verify('/rf-dev-tool/data-base/repair')) { ?>
                                <a class="btn btn-white table-list-database" href="javascript:void(0);" data-type="2">修复表</a>
                            <?php } ?>
                            <!-- 权限校验 -->
                            <?php if (Auth::verify('/rf-dev-tool/data-base/optimize')) { ?>
                                <a class="btn btn-white table-list-database" href="javascript:void(0);" data-type="3">优化表</a>
                            <?php } ?>
                            <!-- 权限校验 -->
                            <?php if (Auth::verify('/rf-dev-tool/data-base/data-dictionary')) { ?>
                                <a class="btn btn-white dictionary" href="javascript:void(0);">Markdown数据字典</a>
                            <?php } ?>
                        </div>
                    </div>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th><input type="checkbox" checked="checked" class="check-all"></th>
                            <th>表备注</th>
                            <th>表名</th>
                            <th>类型</th>
                            <th>记录总数</th>
                            <th>数据大小</th>
                            <th>编码</th>
                            <!-- <th>创建时间</th>-->
                            <!-- <th>备份状态</th>-->
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="list">
                        <?php foreach ($models as $model) { ?>
                            <tr data-name="<?= $model['name'] ?>">
                                <td><input type="checkbox" name="table[]" checked="checked" value="<?= $model['name'] ?>"></td>
                                <td data-table="<?= $model['name'] ?>">
                                    <span class="table <?= $model['name'] ?>"><?= $model['comment'] ?></span> <i class="icon ion-compose" data-toggle="modal" data-target="#editTitle"></i>
                                </td>
                                <td><?= $model['name'] ?></td>
                                <td><?= $model['engine'] ?></td>
                                <td><?= $model['rows'] ?></td>
                                <td><?= Yii::$app->formatter->asShortSize($model['data_length'], 0) ?></td>
                                <td><?= $model['collation'] ?></td>
                                <!-- <td>--><?php //$model['create_time'] ?><!--</td>-->
                                <!--<td id="--><?php //// = $model['name'] ?><!--">未备份</td>-->
                                <td>
                                    <!-- 权限校验 -->
                                    <?php if (Auth::verify('/rf-dev-tool/data-base/optimize')) { ?>
                                        <a href="#" class="btn btn-white table-list-optimize">优化表</a>
                                    <?php } ?>
                                    <!-- 权限校验 -->
                                    <?php if (Auth::verify('/rf-dev-tool/data-base/repair')) { ?>
                                        <a href="#" class="btn btn-white table-list-repair">修复表</a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">表备注</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <textarea type="text" class="form-control" id="tableComment"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <button class="btn btn-primary submit-name" data-dismiss="modal">确定</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var tablename = [];
        // dataType = 1:备份;2:修复;3:优化
        $(".table-list-database").click(function () {
            tablename = [];
            $("#list :checkbox").each(function () {
                if (this.checked) {
                    var table = $(this).val();
                    tablename.push(table);
                }
            });
            var dataType = $(this).attr('data-type');

            if (dataType == 1) {
                rfAffirm('备份中,请不要关闭本页面');
                Export();
            } else if (dataType == 2) {
                rfAffirm('修复中,请不要关闭本页面');
                repair();
            } else if (dataType == 3) {
                rfAffirm('优化中,请不要关闭本页面');
                optimize();
            }
            $('#reminder').show();
        });

        // 优化表单击
        $(".table-list-optimize").click(function () {
            tablename = $(this).parent().parent().data('name');
            rfAffirm('优化中,请不要关闭本页面');
            optimize();
        });

        // 修复表表单击
        $(".table-list-repair").click(function () {
            tablename = $(this).parent().parent().data('name');
            repair();
        });

        // 备份表
        function Export() {
            tablename = [];
            $("#list :checkbox").each(function () {
                if (this.checked) {
                    var table = $(this).val();
                    tablename.push(table);
                }
            });

            $.ajax({
                type: "post",
                url: "<?= Url::to(['export'])?>",
                dataType: 'json',
                data: {tables: tablename},
                success: function (data) {
                    if (data.code == 200) {
                        var id = data.data.tab.id;
                        var start = data.data.tab.start;
                        startExport(id, start);
                    } else {
                        rfAffirm(data.message);
                    }
                }
            })
        }

        // 开始备份
        function startExport(id, start) {
            $.ajax({
                type: "post",
                url: "<?= Url::to(['export-start'])?>",
                dataType: 'json',
                data: {id: id, start: start},
                success: function (data) {
                    if (data.code == 200) {

                        var achieveStatus = data.data.achieveStatus;
                        var tabName = data.data.tablename;
                        $("#" + tabName).text(data.message);

                        if (achieveStatus == 0) {
                            startExport(data.data.tab.id, data.data.tab.start);
                        } else {
                            rfAffirm(data.message);
                        }

                    } else {

                    }
                }
            })
        }

        // 优化表
        function optimize() {
            $.ajax({
                type: "post",
                url: "<?= Url::to(['optimize'])?>",
                dataType: 'json',
                data: {tables: tablename},
                success: function (data) {
                    rfAffirm(data.message);
                }
            })
        }

        // 修复表
        function repair() {
            $.ajax({
                type: "post",
                url: "<?= Url::to(['repair'])?>",
                dataType: 'json',
                data: {tables: tablename},
                success: function (data) {
                    rfAffirm(data.message);
                }
            })
        }

        $(".dictionary").click(function () {
            $.ajax({
                type: "get",
                url: "<?= Url::to(['data-dictionary'])?>",
                dataType: 'json',
                success: function (data) {
                    //自定页
                    layer.open({
                        type: 1,
                        title: '数据字典',
                        skin: 'layui-layer-demo', //样式类名
                        closeBtn: false, //不显示关闭按钮
                        shift: 2,
                        area: ['400px', '80%'],
                        shadeClose: true, //开启遮罩关闭
                        content: data.data.str
                    });
                }
            })
        });

        // 多选框选择
        $(".check-all").click(function () {
            if (this.checked) {
                $("#list :checkbox").prop("checked", true);
            } else {
                $("#list :checkbox").prop("checked", false);
            }
        });
    })

    var table;
    $(document).on("click",".ion-compose",function(){
        table = $(this).parent().data('table');
        var comment = $(this).parent().find('.table').text();
        $('#tableComment').val(comment);
    });

    // 标题编辑
    $(document).on("click",".submit-name",function(){
        var comment = $('#tableComment').val();
        url = "<?= Url::to(['update-table-comment'])?>" + '?table=' + table + '&comment=' + comment;
        $.ajax({
            type: "post",
            url: url,
            dataType: "json",
            success: function (data) {
                if (parseInt(data.code) === 200) {
                    $('.' + table).text(comment);
                    rfMsg('修改成功');
                } else {
                    rfWarning(data.message);
                }
            }
        });
    });
</script>
