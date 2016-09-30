<?php

/**
 * @filename _singleUpload.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-7-27  10:34:11 
 */
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/jsCssSrc/coreJs/ui.widget.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/jsCssSrc/coreJs/iframe-transport.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/jsCssSrc/coreJs/fileupload.js', CClientScript::POS_END);

?>
<style>
    
.fileinput-button {
    position: relative;
    overflow: hidden;
    display: inline-block;
}
.fileinput-button input {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    opacity: 0;
    -ms-filter: 'alpha(opacity=0)';
    font-size: 200px !important;
    direction: ltr;
    cursor: pointer;
}
</style>
<span class="btn btn-default fileinput-button">
    <i class="fa fa-plus"></i>
    <span>选择图片</span>
    <!-- The file input field used as target for the file upload widget -->
    <input id="<?php echo CHtml::activeId($model,$fieldName);?>_upload" type="file" name="<?php echo $fileholder;?>">
</span>
<?php if(!$progress){?>
<div id="progress" class="progress">
    <div class="progress-bar progress-bar-success" style="width: 0%;"></div>
</div>
<?php }?>
<script>
$(function () {
    'use strict';    
    $('#<?php echo CHtml::activeId($model,$fieldName);?>_upload').fileupload({
        url: '<?php echo Yii::app()->createUrl('attachments/upload',array('type'=>$type,'fileholder'=>$fileholder,'imgsize'=>$imgsize));?>',
        dataType: 'json',
        done: function (e, data) {
            var inputId='<?php echo CHtml::activeId($model,$fieldName);?>';
            var targetHolder='<?php echo $targetHolder;?>';
            var result=data.result;
            if(result.status!==1){
                alert(result.msg);
            }else{
                if (inputId) {
                    $('#' + inputId).val(result.imgsrc);
                }
                if (targetHolder) {
                    $('#' + targetHolder).attr('src',result.thumbnail);
                }
            }
            $('#progress').hide();
            $('#progress .progress-bar').css({
                display:'none',
                width:'0%'
            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress').show();
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
</script>