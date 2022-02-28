<?php
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\helpers\Html;
use addons\Wechat\common\enums\RuleKeywordTypeEnum;

$this->title = '自动回复';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

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
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?= Url::to(['rule/index']); ?>"> 关键字自动回复</a></li>
                <li><a href="<?= Url::to(['setting/special-message']); ?>"> 非文字自动回复</a></li>
                <li><a href="<?= Url::to(['reply-default/index']); ?>"> 关注/默认回复</a></li>
                <li class="pull-right">
                    <?= Html::a('<i class="icon ion-plus"></i> 创建', ['edit'], [
                        'class' => 'btn btn-primary btn-xs'
                    ])?>
                </li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="btn-group">
                                <a class="btn <?= !$module ? 'btn-primary': 'btn-white'; ?>" href="<?= Url::to(['index']); ?>">全部</a>
                                <?php foreach ($modules as $key => $mo){ ?>
                                    <a class="btn <?= $module == $key ? 'btn-primary': 'btn-white' ;?>" href="<?= Url::to(['index','module' => $key])?>"><?= $mo?></a>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <?php $form = ActiveForm::begin([
                                'action' => Url::to(['index']),
                                'method' => 'get'
                            ]); ?>
                            <div class="input-group m-b">
                                <?= Html::textInput('keyword', $keyword, [
                                    'placeholder' => '请输入规则',
                                    'class' => 'form-control'
                                ])?>
                                <?= Html::tag('span', '<button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn'])?>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <?php foreach($models as $model){ ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <span class="collapsed"><?= $model->name ?></span>
                                <span class="float-right" id="<?= $model->id ?>">
                                    <span class="label label-info">优先级：<?= $model->sort; ?></span>
                                    <?php if(Yii::$app->wechatService->ruleKeyword->verifyTake($model->ruleKeyword)){ ?>
                                        <span class="label label-info">直接接管</span>
                                    <?php } ?>
                                    <?php if($model->status == StatusEnum::ENABLED){ ?>
                                        <span class="label label-info pointer" onclick="statusRule(this)">已启用</span>
                                    <?php }else{ ?>
                                        <span class="label label-danger pointer" onclick="statusRule(this)">已禁用</span>
                                    <?php } ?>
                                </span>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="true" style="">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-9 tooltip-demo">
                                            <?php if($model->ruleKeyword){ ?>
                                                <?php foreach($model->ruleKeyword as $rule){
                                                    if($rule->type != RuleKeywordTypeEnum::TAKE){ ?>
                                                        <span class="label label-default" data-toggle="tooltip" data-placement="bottom" title="<?= RuleKeywordTypeEnum::getValue($rule->type); ?>"><?= $rule->content?></span>
                                                    <?php }
                                                }
                                            } ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="btn-group float-right">
                                                <?= Html::linkButton(['edit', 'id' => $model->id, 'module' => $model->module], '<i class="fa fa-edit"></i> 编辑')?>
                                                <?= Html::delete(['delete', 'id' => $model->id], '<i class="fa fa-times"></i> 删除', [
                                                    'class' => 'btn btn-white btn-sm'
                                                ])?>
                                                <!-- <a class="btn btn-white btn-sm" href="#"><i class="fa fa-bar-chart-o"></i> 使用率走势</a>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <?= LinkPager::widget([
                                'pagination' => $pages,
                            ]);?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // status => 1:启用;-1禁用;
    function statusRule(obj){
        var id = $(obj).parent().attr('id');
        var self = $(obj);
        var status = self.hasClass("label-danger") ? 1 : 0;

        $.ajax({
            type:"get",
            url:"<?= Url::to(['ajax-update'])?>",
            dataType: "json",
            data: {id:id,status:status},
            success: function(data){
                if(data.code == 200) {
                    if(self.hasClass("label-danger")){
                        self.removeClass("label-danger").addClass("label-info");
                        self.text('已启用');
                    } else {
                        self.removeClass("label-info").addClass("label-danger");
                        self.text('已禁用');
                    }
                }else{
                    alert(data.message);
                }
            }
        });
    }
</script>
