<?php

use yii\widgets\ActiveForm;
use common\enums\StatusEnum;
use common\helpers\Url;
use common\helpers\Html;
use addons\Wechat\merchant\widgets\selector\Select;
use addons\Wechat\common\enums\RuleKeywordTypeEnum;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '自动回复', 'url' => ['rule/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= Html::cssFile('@web/resources/css/checkbox.css'); ?>

<?php $form = ActiveForm::begin([
    'id' => 'ruleForm',
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['edit', 'id' => $model['id']]),
]); ?>


<style>
    .panel-default {
        border-color: #ddd;
    }
    .panel {
        margin-bottom: 20px;
        background-color: #fff;
        border: 1px solid transparent;
        border-radius: 4px;
        -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
        box-shadow: 0 1px 1px rgba(0,0,0,.05);
    }

    .panel-default > .panel-heading {
        color: #333;
        background-color: #f5f5f5;
        border-color: #ddd;
    }
    .panel-heading {
        padding: 10px 15px;
        border-bottom: 1px solid transparent;
        border-bottom-color: transparent;
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
    }

    .collapse.in {
        display: block;
    }
    .collapse {
        display: none;
    }

    .panel-default > .panel-heading + .panel-collapse > .panel-body {
        border-top-color: #ddd;
    }
    .panel-body {
        padding: 15px;
    }

    .panel-default {
        border-color: #e4eaec;
    }
    .panel {
        border: 1px solid #e4eaec;
        border-top-color: rgb(228, 234, 236);
        border-right-color: rgb(228, 234, 236);
        border-bottom-color: rgb(228, 234, 236);
        border-left-color: rgb(228, 234, 236);
    }
</style>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="collapsed">回复规则</span>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="true">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-9">
                            <?= $form->field($model, 'name')->textInput()->hint('您可以给这条规则起一个名字, 方便下次修改和查看。') ?>
                            <div class="setting" style="display: none">
                                <?= $form->field($model, 'sort')->textInput()->hint('规则优先级，越大则越靠前，最大不得超过255') ?>
                            </div>
                        </div>
                        <div class="col-sm-3 col-md-2 m-t-md">
                            <div class="checkbox">
                                <?= Html::checkbox('setting', false, [
                                    'class' => "styled adv",
                                    'id' => 'setting',
                                ]); ?>
                                <label for="setting">高级设置</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-9">
                            <?= $form->field($model, 'keyword')->textInput(['value'=> implode(',',$ruleKeywords[RuleKeywordTypeEnum::MATCH])])->hint('多个关键字请使用逗号隔开，如天气，今日天气。')->label('关键字') ?>
                            <div class="trigger" style="display: none">
                                <div class="form-group">
                                    <label class="control-label">高级触发列表</label>
                                </div>
                                <div class="form-group well">
                                    <div class="tabs-container">
                                        <div class="tabs-top">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-link active"><a data-toggle="tab" href="#tab-11" aria-expanded="true"> 包含关键字</a></li>
                                                <li class="nav-link"><a data-toggle="tab" href="#tab-12" aria-expanded="false"> 正则表达式模式匹配</a></li>
                                                <li class="nav-link"><a data-toggle="tab" href="#tab-13" aria-expanded="false"> 直接接管</a></li>
                                            </ul>
                                            <div class="tab-content ">
                                                <div id="tab-11" class="tab-pane active">
                                                    <div class="panel-body">
                                                        <table class="table table-hover">
                                                            <tbody id="list-key-<?= RuleKeywordTypeEnum::INCLUDE?>">
                                                            <?php foreach ($ruleKeywords[RuleKeywordTypeEnum::INCLUDE] as $value){ ?>
                                                                <tr>
                                                                    <td class="saveKeywordInput">
                                                                        <span class="key-hint"></span>
                                                                        <span class="key-text"><?= $value?></span>
                                                                        <input type="text" class="form-control key-input" name="ruleKey[<?= RuleKeywordTypeEnum::INCLUDE ?>][]" value="<?= $value?>" style="display: none;">
                                                                    </td>
                                                                    <td type="<?= RuleKeywordTypeEnum::INCLUDE ?>">
                                                                        <span class="btn btn-white saveKeyword edit-Keyword">编辑</span>
                                                                        <span class="btn btn-white" onclick="$(this).parent().parent().remove()">删除</span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="panel-heading" type="<?= RuleKeywordTypeEnum::INCLUDE?>">
                                                        <span class="btn btn-white addKeyword">添加包含关键字</span>
                                                        <span class="help-block ng-binding">用户进行交谈时，对话中包含上述关键字就执行这条规则。</span>
                                                    </div>
                                                </div>
                                                <div id="tab-12" class="tab-pane">
                                                    <div class="panel-body">
                                                        <table class="table table-hover">
                                                            <tbody id="list-key-<?= RuleKeywordTypeEnum::REGULAR?>">
                                                            <?php foreach ($ruleKeywords[RuleKeywordTypeEnum::REGULAR] as $value){ ?>
                                                                <tr>
                                                                    <td class="saveKeywordInput">
                                                                        <span class="key-hint"></span>
                                                                        <span class="key-text"><?= $value?></span>
                                                                        <input type="text" class="form-control key-input" name="ruleKey[<?= RuleKeywordTypeEnum::REGULAR ?>][]" value="<?= $value?>" style="display: none;">
                                                                    </td>
                                                                    <td type="<?= RuleKeywordTypeEnum::REGULAR ?>">
                                                                        <span class="btn btn-white saveKeyword edit-Keyword">编辑</span>
                                                                        <span class="btn btn-white" onclick="$(this).parent().parent().remove()">删除</span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="panel-heading" type="<?= RuleKeywordTypeEnum::REGULAR?>">
                                                        <span class="btn btn-white addKeyword">添加正则表达式模式</span>
                                                        <span class="help-block ng-binding">
                                                            用户进行交谈时，对话内容符合述关键字中定义的模式才会执行这条规则。<br>
                                                            <strong>注意：如果你不明白正则表达式的工作方式，请不要使用正则匹配</strong> <br>
                                                            <strong>注意：正则匹配使用MySQL的匹配引擎，请使用MySQL的正则语法</strong> <br>
                                                            <strong>示例: </strong><br>
                                                            <label>^微信</label>  匹配以“微信”开头的语句<br>
                                                            <label>微信$</label>  匹配以“微信”结尾的语句<br>
                                                            <label>^微信$</label>  匹配等同“微信”的语句<br>
                                                            <label>微信</label>  匹配包含“微信”的语句<br>
                                                            <label>[0-9.-]</label>  匹配所有的数字，句号和减号<br>
                                                            <label>^[a-zA-Z_]$</label>  匹配所有的字母和下划线<br>
                                                            <label>^[[:alpha:]]{3}$</label>  匹配所有的3个字母的单词<br>
                                                            <label>^a{4}$</label>  匹配aaaa<br>
                                                            <label>^a{2,4}$</label>  匹配aa，aaa或aaaa<br>
                                                            <label>^a{2,}$</label>  匹配多于两个a的字符串
                                                        </span>
                                                    </div>
                                                </div>
                                                <div id="tab-13" class="tab-pane">
                                                    <div class="panel-body">
                                                        <table class="table">
                                                            <tbody id="list-key-<?= RuleKeywordTypeEnum::TAKE?>">
                                                            <?php foreach ($ruleKeywords[RuleKeywordTypeEnum::TAKE] as $value){ ?>
                                                                <tr>
                                                                    <td>
                                                                        <span class="key-text">符合优先级条件时, 这条回复将直接生效</span>
                                                                        <input type="hidden" name="ruleKey[<?= RuleKeywordTypeEnum::TAKE ?>][]" value="">
                                                                    </td>
                                                                    <td type="<?= RuleKeywordTypeEnum::TAKE ?>">
                                                                        <span class="btn btn-white" onclick="$(this).parent().parent().remove()">取消接管</span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="panel-heading" type="<?= RuleKeywordTypeEnum::TAKE?>">
                                                        <span class="btn btn-white addKeyword">直接接管</span>
                                                        <span class="help-block ng-binding">
                                                                如果没有比这条回复优先级更高的回复被触发，那么直接使用这条回复。<br>
                                                                <strong>注意：如果你不明白这个机制的工作方式，请不要使用直接接管</strong>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 col-md-2 m-t-md">
                            <div class="checkbox">
                                <?= Html::checkbox('trigger', false, [
                                    'class' => "styled adv",
                                    'id' => 'trigger',
                                ]); ?>
                                <label for="trigger">高级设置</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-9">
                            <?= $form->field($model, 'status')->radioList(StatusEnum::getMap())->hint('您可以临时禁用这条回复') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-sm-12">
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item"><a class="nav-link text active" data-toggle="pill" href="#custom-1" onclick="setType('text')">内容</a></li>
                <li class="nav-item"><a class="nav-link image" data-toggle="pill" href="#custom-2" onclick="setType('image')">图片</a></li>
                <li class="nav-item"><a class="nav-link news" data-toggle="pill" href="#custom-3" onclick="setType('news')">图文</a></li>
                <li class="nav-item"><a class="nav-link video" data-toggle="pill" href="#custom-4" onclick="setType('video')">视频</a></li>
                <li class="nav-item"><a class="nav-link voice" data-toggle="pill" href="#custom-5" onclick="setType('voice')">语音</a></li>
                <li class="nav-item"><a class="nav-link user-api" data-toggle="pill" href="#custom-6" onclick="setType('user-api')">自定义接口</a></li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade active show" id="custom-1">
                    <?= $form->field($model, 'text')->textarea([
                        'id' => 'content'
                    ])->label(false) ?>
                </div>
                <div class="tab-pane fade" id="custom-2">
                    <?= $form->field($model, 'image')->widget(Select::class, [
                        'type' => 'image',
                    ])->label(false) ?>
                </div>
                <div class="tab-pane fade" id="custom-3">
                    <?= $form->field($model, 'news')->widget(Select::class, [
                        'type' => 'news',
                        'block' => '由于微信限制，自动回复只能回复一条图文信息，如果有多条图文，默认选择第一条图文',
                    ])->label(false) ?>
                </div>
                <div class="tab-pane fade" id="custom-4">
                    <?= $form->field($model, 'video')->widget(Select::class, [
                        'type' => 'video',
                    ])->label(false) ?>
                </div>
                <div class="tab-pane fade" id="custom-5">
                    <?= $form->field($model, 'voice')->widget(Select::class, [
                        'type' => 'voice',
                    ])->label(false) ?>
                </div>
                <div class="tab-pane fade" id="custom-6">
                    <?= $form->field($model, 'api_url')->dropDownList($apiList)->hint('1、添加此模块的规则后，只针对于单个规则定义有效，如果需要全部路由给接口处理，则修改该模块的优先级顺序<br>2、本地文件存放在当前插件文件夹内的(/addons/Wechat/common/userapis)下<br>3、文件名格式为*Api.php，例如：TestApi.php') ?>
                    <?= $form->field($model, 'default')->textInput()->hint('当接口无回复时，则返回用户此处设置的文字信息，优先级高于“默认关键字”') ?>
                    <?= $form->field($model, 'cache_time')->textInput()->hint('接口返回数据将缓存在系统中的时限，默认为0不缓存') ?>
                    <?= $form->field($model, 'description')->textarea()->hint('仅作为后台备注接口的用途') ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $form->field($model, 'module')->hiddenInput(['id' => 'module'])->label(false) ?>

<div class="row">
    <div class="col-12  text-center">
        <span class="btn btn-primary" onclick="beforeSubmit()">保存</span>
        <span class="btn btn-white" onclick="history.go(-1)">返回</span>
    </div>
</div>

<?php ActiveForm::end(); ?>

<!--关键字模板-->
<script id="keylist" type="text/html">
    <tr>
        <td class="saveKeywordInput">
            <span class="key-hint"></span>
            <span class="key-text"></span>
            <input type="text" class="form-control key-input">
        </td>
        <td type="{{type}}">
            <span class="btn btn-white saveKeyword save-Keyword">保存</span>
            <span class="btn btn-white" onclick="$(this).parent().parent().remove()">删除</span>
        </td>
    </tr>
</script>

<!--直接授权模板-->
<script id="keytask" type="text/html">
    <tr>
        <td>
            <span class="key-text">符合优先级条件时, 这条回复将直接生效</span>
            <input type="hidden" name="ruleKey[{{type}}][]" value="1">
        </td>
        <td>
            <span class="btn btn-white" onclick="$(this).parent().parent().remove()">取消接管</span>
        </td>
    </tr>
</script>

<script>
    var type = 'text';// 1:文字;2:图片;3:图文;4:视频;5:音频;6:自定义接口
    var module = '<?= $model->module; ?>';
    var modules = '<?= $modules; ?>';
    modules = JSON.parse(modules)
    // 设置类型
    function setType(num) {
        type = num;
    }

    function beforeSubmit() {
        var val = description = title = '';
        var id = "<?= $model['id']; ?>";
        $('#module').val(type);
        $('#ruleForm').submit();
    }

    $(document).ready(function(){
        if (module) {
            $('.' + module).trigger('click')
        }

        // 单击展开
        $('.adv').click(function () {
            var id = $(this).attr('id');
            if($(this).is(':checked')) {
                // do something
                $('.' + id).show();
            }else{
                $('.' + id).hide();
            }
        });

        // 展开高级触发
        if ($('#list-key-2').html().length > 121 || $('#list-key-3').html().length > 121 || $('#list-key-4').html().length > 121){
            $('#trigger').trigger('click');
        }

        // 添加关键字
        var take = "<?= RuleKeywordTypeEnum::TAKE?>";
        $('.addKeyword').click(function(){
            var self = $(this);
            var type = self.parent().attr('type');
            var data = [];
            data.type = type;
            var html = "";
            if(type == take){
                html += template('keytask', data);
                if($('#list-key-'+type+" tr").length < 1){
                    $('#list-key-'+type).append(html);
                }
            }else{
                html += template('keylist', data);
                $('#list-key-'+type).append(html);
            }
        });

        // 保存编辑
        $(document).on("click",".saveKeyword",function(){
            var self = $(this);
            var val = self.parent().parent().find('.key-input').val();
            var type = self.parent().attr('type');
            if (self.hasClass("save-Keyword")){// 保存
                if(val){
                    self.removeClass("save-Keyword").addClass("edit-Keyword");
                    self.text('编辑');
                    self.parent().parent().find('.key-text').text(val);
                    self.parent().parent().find('.key-text').show();
                    self.parent().parent().find('.key-input').hide();
                    self.parent().parent().find('.key-input').attr('name','ruleKey['+type+'][]');
                }
            } else {// 编辑
                self.removeClass("edit-Keyword").addClass("save-Keyword");
                self.text('保存');
                self.parent().parent().find('.key-text').hide();
                self.parent().parent().find('.key-input').show();
                self.parent().parent().find('.key-input').attr('name','');
            }
        });

        $("input[name='SendForm[send_type]']").click(function(){
            var val = $(this).val();
            if (val == 1) {
                $('#send_time').addClass('hide');
            } else {
                $('#send_time').removeClass('hide');
            }
        })
    });
</script>