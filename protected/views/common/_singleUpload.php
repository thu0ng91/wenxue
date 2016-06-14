<?php

/**
 * @filename upload.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-23  17:13:52 
 */
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/common/uploadify/jquery.uploadify.min.js', CClientScript::POS_END);
?>
<div id="<?php echo CHtml::activeId($model,$fieldName);?>_upload"></div>
<div id="fileSuccess"></div>
<div id="singleFileQueue"></div>
<script>
    $(document).ready(
            function () {
                uploadByLimit({
                    placeHolder:'<?php echo CHtml::activeId($model,$fieldName);?>_upload',
                    inputId:'<?php echo CHtml::activeId($model,$fieldName);?>',
                    targetHolder:'<?php echo $targetHolder;?>',
                    limit:<?php echo isset($num) ? $num : 30; ?>,
                    uploadUrl:"<?php echo Yii::app()->createUrl('attachments/upload',array('type'=>$type,'fileholder'=>$fileholder));?>",
                    type:'posts',
                    token:'<?php echo $token;?>',
                    filedata:'<?php echo $fileholder; ?>',
                    height:<?php echo isset($height) ? $height : 36; ?>,
                    width:<?php echo isset($width) ? $width : 110; ?>,
                    buttonClass:'btn btn-success',
                    multi:false
                });
            });   
</script>