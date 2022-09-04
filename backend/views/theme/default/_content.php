<?php

use common\helpers\Url;

?>

<div class="content-wrapper iframe-mode" data-widget="iframe">
    <div class="content-tabs hide">
        <button class="roll-nav roll-left J_tabLeft"><i class="iconfont iconhoutui"></i></button>
        <nav class="page-tabs J_menuTabs" id="rftags">
            <div class="page-tabs-content">
                <a href="javascript:void (0);" class="active J_menuTab" data-id="<?= Url::to(Yii::$app->params['adminDefaultHomePage']); ?>" id="rftagsIndexLink">首页</a>
                <!--默认主页需在对应的选项卡a元素上添加data-id="默认主页的url"-->
            </div>
        </nav>
        <button class="roll-nav roll-right J_tabRight"><i class="iconfont iconqianjin"></i></button>
        <div class="btn-group roll-nav roll-right">
            <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作</button>
            <ul role="menu" class="dropdown-menu dropdown-menu-right">
                <li class="J_tabShowActive"><a>定位当前选项卡</a></li>
                <li class="divider"></li>
                <li class="J_tabCloseAll"><a>关闭全部选项卡</a></li>
                <li class="J_tabCloseOther"><a>关闭其他选项卡</a></li>
            </ul>
        </div>
    </div>
    <div class="J_mainContent" id="content-main">
        <!--默认主页需在对应的页面显示iframe元素上添加name="iframe0"和data-id="默认主页的url"-->
        <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="<?= Url::to(Yii::$app->params['adminDefaultHomePage']); ?>" frameborder="0" data-id="<?= Url::to(Yii::$app->params['adminDefaultHomePage']); ?>" seamless></iframe>
    </div>
</div>
