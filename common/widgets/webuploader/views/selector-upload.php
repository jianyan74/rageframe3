<?php

use common\enums\AttachmentUploadTypeEnum;

?>

<div class="btn btn-primary selector-upload-album" id="<?= $boxId; ?>">上传<?= AttachmentUploadTypeEnum::getValue($type); ?></div>
<!--隐藏上传组件-->
<div class="hidden" id="upload-<?= $boxId; ?>">
    <div class="upload-album-<?= $boxId; ?>"></div>
</div>

<script>
    var boxId = "<?= $boxId; ?>";
    // 触发上传
    $(document).on("click", ".selector-upload-album",function(e){
        let boxId = $(this).attr('id');
        $('#upload-' + boxId + ' .webuploader-container input').trigger('click');
    });

    // 上传成功
    $(document).on('upload-success-' + boxId, function(e, data, config){
        location.reload();
    });

    // 上传失败
    $(document).on('upload-error-' + boxId, function(e, file, reason, uploader, config){
        uploader.removeFile(file); //从队列中移除
        rfError("上传失败，服务器错误");
    });
</script>
