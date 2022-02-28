<?php

use common\helpers\Url;

$this->title = 'JsTree 无限极分类';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<?= \common\widgets\jstree\JsTreeTable::widget([
    'title' => 'JsTree 无限极分类',
    'name' => "userTree",
    'defaultData' => $data,
    'editUrl' => Url::to(['edit']), // 编辑
    'deleteUrl' => Url::to(['delete']), // 删除
    'moveUrl' => Url::to(['move']), // 移动
]) ?>
