<?php

use common\helpers\Html;
use common\enums\StatusEnum;
use common\enums\MemberAuthOauthClientEnum;

$this->title = '第三方授权';

?>

<div class="callout callout-dark">
    <h5><i class="iconfont iconbaocuo help"></i> 操作提示</h5>
    如果是绑定微信，需要先安装微信公众号插件，否则会导致绑定不成功。<br>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">第三方授权</h3>
            </div>
            <div class="box-body">
                <table class="table table-hover" style="margin-top: 10px">
                    <thead>
                    <tr>
                        <th>绑定类型</th>
                        <th>绑定账号</th>
                        <th>绑定状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($thirdParty as $item) { ?>
                        <tr>
                            <td><?= $item['title'];?> </td>
                            <td><?= Html::encode($item['client']) ?></td>
                            <td>
                                <?php if($item['status'] == StatusEnum::DISABLED) { ?>
                                    <span class="label label-outline-danger">未绑定</span>
                                <?php } else { ?>
                                    <span class="label label-outline-success">已绑定</span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if($item['status'] == StatusEnum::DISABLED) { ?>
                                    <?= Html::a('立即绑定', ['binding-wechat', 'member_id' => $memberId, 'type' => MemberAuthOauthClientEnum::WECHAT], [
                                        'class' => 'cyan',
                                        'data-fancybox' => 'gallery',
                                    ])?>
                                <?php } else { ?>
                                    <?= Html::a('解绑', ['un-bind', 'member_id' => $memberId, 'type' => MemberAuthOauthClientEnum::WECHAT], [
                                        'class' => 'red'
                                    ])?>
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
