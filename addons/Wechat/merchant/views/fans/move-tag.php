<?php

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\helpers\Html;

?>

<?php $form = ActiveForm::begin([]); ?>

<div class="modal-header">
    <h4 class="modal-title">用户标签</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>

<div class="modal-body">
    <?= Html::checkboxList('tag_id', $fansTags, ArrayHelper::map($tags, 'id', 'name')); ?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
