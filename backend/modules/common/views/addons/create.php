<?php

use yii\widgets\ActiveForm;
use common\helpers\ArrayHelper;
use common\helpers\Url;
use yii\helpers\Json;

$this->title = '设计新插件';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<style>
    .well {
        background-color: #f5f5f5;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['index']) ?>"> 已安装的插件</a></li>
                <li><a href="<?= Url::to(['local']) ?>"> 安装插件</a></li>
                <li class="active"><a href="<?= Url::to(['create']) ?>"> 设计新插件</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane rf-auto">
                    <div class="col-lg-12">
                        <?php $form = ActiveForm::begin([
                            'options' => [
                                'enctype' => 'multipart/form-data'
                            ],
                        ]); ?>
                        <div class="row">
                            <div class="col-12">
                                <?= $form->field($model, 'title')->textInput()->hint('显示在用户的插件列表中. 不要超过20个字符') ?>
                                <?= $form->field($model, 'name')->textInput()->hint('应对应插件文件夹的名称, 系统按照此标识符查找插件定义, 只能英文和下划线组成，建议大写驼峰，例如：RfArticle') ?>
                                <?= $form->field($model, 'author')->textInput() ?>
                                <?= $form->field($model, 'version')->textInput()->hint('此版本号用于插件的版本更新') ?>
                                <?= $form->field($model, 'brief_introduction')->textInput() ?>
                                <?= $form->field($model, 'description')->textarea()->hint('详细介绍插件的功能和使用方法 ') ?>
                                <?= $form->field($model, 'group')->dropDownList(ArrayHelper::map($addonsGroup, 'name', 'title')) ?>
                                <?= $form->field($model, 'is_merchant_route_map')->checkbox()->hint('开启后会将商户端的 url 直接映射到后台来，节省相同代码，请了解后再使用') ?>
                                <div class="hr-line-dashed"></div>
                                <div id="app">
                                    <div class="form-group desk-menu">
                                        <label class="control-label">总后台菜单</label>
                                    </div>
                                    <div class="well well-sm">
                                        <div v-for="(list, index) in backendList">
                                            <div class="col-12 col-sm-12 row mt-2 mb-2">
                                                <div class="col-2 col-md-2">
                                                    <div class="input-group">
                                                        <span class="btn btn-white">名称</span>
                                                        <input class="form-control" name="menu[backend][title][]" placeholder="首页管理" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-2 col-md-2">
                                                    <div class="input-group">
                                                        <span class="btn btn-white">路由</span>
                                                        <input class="form-control" name="menu[backend][name][]" placeholder="test/index" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-2 col-md-2">
                                                    <div class="input-group">
                                                        <span class="btn btn-white">图标</span>
                                                        <input class="form-control" name="menu[backend][icon][]" placeholder="fa fa-wechat" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-5 col-md-5">
                                                    <div class="input-group">
                                                        <span class="btn btn-white">参数</span>
                                                        <input class="form-control" name="menu[backend][params][]" type="text" readonly>
                                                        <span class="btn btn-white editValue" data-toggle="modal" data-target="#ajaxModalLgForAttribute">编辑</span>
                                                    </div>
                                                </div>
                                                <div class="col-1 col-md-1">
                                                    <div style="margin-top:7px">
                                                        <a class="icon ion-android-cancel" href="javascript:void(0);" onclick="$(this).parent().parent().parent().remove()"></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0);" class="m-l" @click="add('backend')">添加菜单 <i class="iconfont iconplus-circle" title="添加菜单"></i></a>
                                    </div>
                                    <div class="form-group desk-menu">
                                        <label class="control-label">商家菜单</label>
                                    </div>
                                    <div class="well well-sm">
                                        <div v-for="(list, index) in merchantList">
                                            <div class="col-12 col-sm-12 row mt-2 mb-2">
                                                <div class="col-2 col-md-2">
                                                    <div class="input-group">
                                                        <span class="btn btn-white">名称</span>
                                                        <input class="form-control" name="menu[merchant][title][]" placeholder="首页管理" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-2 col-md-2">
                                                    <div class="input-group">
                                                        <span class="btn btn-white">路由</span>
                                                        <input class="form-control" name="menu[merchant][name][]" placeholder="test/index" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-2 col-md-2">
                                                    <div class="input-group">
                                                        <span class="btn btn-white">图标</span>
                                                        <input class="form-control" name="menu[merchant][icon][]" placeholder="fa fa-wechat" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-5 col-md-5">
                                                    <div class="input-group">
                                                        <span class="btn btn-white">参数</span>
                                                        <input class="form-control" name="menu[merchant][params][]" type="text" readonly>
                                                        <span class="btn btn-white editValue" data-toggle="modal" data-target="#ajaxModalLgForAttribute">编辑</span>
                                                    </div>
                                                </div>
                                                <div class="col-1 col-md-1">
                                                    <div style="margin-top:7px">
                                                        <a class="icon ion-android-cancel" href="javascript:void(0);" onclick="$(this).parent().parent().parent().remove()"></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0);" class="m-l" @click="add('merchant')">添加菜单 <i class="iconfont iconplus-circle" title="添加菜单"></i></a>
                                    </div>
                                </div>
                                <div class="hint-block">会在顶部导航菜单或者应用中心入口创建菜单列表</div>
                                <div class="hr-line-dashed"></div>
                                <?= $form->field($model,
                                    'install')->textInput()->hint('当前插件全新安装时所执行的脚本, 指定为单个的php脚本文件, 如: Install.php') ?>
                                <?= $form->field($model,
                                    'uninstall')->textInput()->hint('当前插件卸载时所执行的脚本, 指定为单个的php脚本文件, 如: UnInstall.php') ?>
                                <?= $form->field($model,
                                    'upgrade')->textInput()->hint('当前插件更新时所执行的脚本, 指定为单个的php脚本文件, 如: Upgrade.php') ?>
                            </div>
                            <div class="col-12 text-center">
                                <div class="hr-line-dashed"></div>
                                <button class="btn btn-primary" type="submit">保存</button>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--模拟框加载 -->
<div class="modal fade" id="ajaxModalLgForAttribute" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">参数</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <?= \common\helpers\Html::textarea('value', '', [
                    'class'=> 'form-control',
                    'id' => 'tmpValue',
                    'style' => 'height:200px',
                    'placeholder' => '例如：status:1',
                ]);?>
                <div class="help-block">
                    一行为一个k-v参数值，多个参数值用换行输入<br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <button class="btn btn-primary js-confirm" data-dismiss="modal">保存</button>
            </div>
        </div>
    </div>
</div>

<script>
    var app = new Vue({
        el: '#app',
        data: {
            backendList: [[]],
            merchantList: [[]],
        },
        methods: {
            add: function (menuType) {
                if (menuType === 'backend') {
                    this.backendList.push([]);
                } else {
                    this.merchantList.push([]);
                }
            },
        },
        // 初始化
        mounted () {

        },
    });

    // 编辑参数值
    $(document).on("click", ".editValue",function(){
        editValue = $(this).parent();
        var value = $(editValue).find('input').val();

        if (value) {
            var value = value.split(',');
            var html = '';
            console.log(value);
            for (var i = 0; i < value.length; i++) {
                if(value[i] !== ""){
                    if ((i+1) == value.length) {
                        html += value[i]
                    } else {
                        html += value[i] + "\n";
                    }
                }
            }
        }

        $('#tmpValue').val(html);
    });

    // 确定编辑参数
    $(document).on("click", ".js-confirm",function(){
        var tmpVal = $('#tmpValue').val();
        var value = tmpVal.split("\n");
        var html = '';
        for (var i = 0; i < value.length; i++) {
            if(value[i] !== "" && value[i].length > 0){
                if ((i+1) == value.length) {
                    html += value[i]
                } else {
                    html += value[i] + ",";
                }
            }
        }

        $(editValue).parent().find('input').val(html);
    });
</script>
