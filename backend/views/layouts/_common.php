<?php

use common\helpers\Html;
use common\helpers\DebrisHelper;
use common\helpers\StringHelper;

?>

<!--ajax模拟框加载-->
<div class="modal fade" id="ajaxModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body" style="padding: 0">
                <div style="padding: 20px 30px">
                    <?= Html::img('@baseResources/img/loading.gif', ['class' => 'loading']) ?>
                    <span>加载中... </span>
                </div>
            </div>
        </div>
    </div>
</div>
<!--ajax大模拟框加载-->
<div class="modal fade" id="ajaxModalLg" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body" style="padding: 0">
                <div style="padding: 20px 30px">
                    <?= Html::img('@baseResources/img/loading.gif', ['class' => 'loading']) ?>
                    <span>加载中... </span>
                </div>
            </div>
        </div>
    </div>
</div>
<!--ajax最大模拟框加载-->
<div class="modal fade" id="ajaxModalMax" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="width: 80%">
        <div class="modal-content">
            <div class="modal-body" style="padding: 0">
                <div style="padding: 20px 30px">
                    <?= Html::img('@baseResources/img/loading.gif', ['class' => 'loading']) ?>
                    <span>加载中... </span>
                </div>
            </div>
        </div>
    </div>
</div>
<!--初始化模拟框-->
<div id="rfModalBody" style="display: none;">
    <div class="modal-body" style="padding: 0">
        <div style="padding: 20px 30px">
            <?= Html::img('@baseResources/img/loading.gif', ['class' => 'loading']) ?>
            <span>加载中... </span>
        </div>
    </div>
</div>

<?php

list($fullUrl, $pageConnector) = DebrisHelper::getPageSkipUrl();

$page = (int)Yii::$app->request->get('page', 1);
$perPage = (int)Yii::$app->request->get('per-page', 10);

$perPageSelect = Html::dropDownList('rf-per-page', $perPage, [
    10 => '10条/页',
    15 => '15条/页',
    25 => '25条/页',
    40 => '40条/页',
], [
    'class' => 'form-control rf-per-page',
    'style' => 'width:100px'
]);

$perPageSelect = StringHelper::replace("\n", '', $perPageSelect);

$script = <<<JS
    $(".pagination").append('<li style="float: left;margin-left: 10px;">$perPageSelect</li>');
    $(".pagination").append('<li>&nbsp;&nbsp;前往&nbsp;<input id="invalue" type="text" class="pane rf-page-skip-input"/>&nbsp;页</li>');
    $(".pagination").removeClass('hide');
    
    // 跳转页码
    $('.rf-page-skip-input').blur(function() {
        var page = $('#invalue').val();
        if (!page) {
            return;
        }
        
        if (parseInt(page) > 0) {
              location.href = "{$fullUrl}" + "{$pageConnector}page="+ parseInt(page) + '&per-page=' + parseInt($('.rf-per-page').val());
        } else {
            $('#invalue').val('');
            rfAffirm('请输入正确的页码');
        }
    });
    
    // 选择分页数量
    $('.rf-per-page').change(function() {
        var page = $('#invalue').val();
        if (!page) {
            page = '{$page}';
        }
  
        location.href = "{$fullUrl}" + "{$pageConnector}page="+ parseInt(page) + '&per-page=' + parseInt($(this).val());
    });
JS;

$this->registerJs($script);
?>

<script>
    $('#ajaxModal,#ajaxModalLg,#ajaxModalMax').on('hidden.bs.modal',  function (e) {
        if (e.target == this) {
            $(this).removeData("bs.modal");
            $(this).find('.modal-content').html($('#rfModalBody').html());
        }
    }).on('shown.bs.modal', function (e) {
        var thatModalContent = $(this).find('.modal-body');
        $.ajax({
            type: "get",
            url: $(e.relatedTarget).attr('href'),
            success: function (data) {
                thatModalContent.html(data);
                autoFontColor();
            }
        });
        autoFontColor();
    });
</script>

