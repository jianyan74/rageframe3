<?php

use common\helpers\Url;

$this->title = '省市区';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<?= \common\widgets\jstree\JsTreeTable::widget([
    'title' => '省市区',
    'name' => "userTree",
    'ajax' => true,
    'listUrl' => Url::to(['list']), // 加载
    'editUrl' => Url::to(['edit']), // 编辑
    'deleteUrl' => Url::to(['delete']), // 删除
    'moveUrl' => Url::to(['move']), // 移动
]) ?>