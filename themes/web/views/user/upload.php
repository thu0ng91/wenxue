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
Yii::import('application.vendors.qiniu.*');
require_once 'autoload.php';
use Qiniu\Auth;
$accessKey = zmf::config('qiniuAk');
$secretKey = zmf::config('qiniuSk');
$bucket = zmf::config('qiniuBucket');
if($accessKey && $secretKey && $bucket){
    $auth = new Auth($accessKey, $secretKey);                
    $token = $auth->uploadToken($bucket);
}
?>
<style>
    #noModelUpload{
        margin: 0 auto 15px;
    }
</style>
<div class="module">
    <div class="module-header">
        上传图片
    </div>
    <div class="module-body">
        <div id="noModelUpload"></div>
        <div id="fileSuccess" class="fileSuccess"></div>
        <div id="singleFileQueue" style="clear:both;display: "></div>        
    </div>
</div>
<script>
    $(document).ready(
            function () {
                singleUploadify({
                    placeHolder:'noModelUpload',
                    inputId:'',
                    limit:<?php echo isset($num) ? $num : 30; ?>,
                    uploadUrl:"http://upload.qiniu.com/",
                    type:'posts',
                    token:'<?php echo $token;?>',
                    filedata:'file',
                    height:36,
                    width:134,
                    buttonClass:'btn btn-success'
                });
            });   
</script>