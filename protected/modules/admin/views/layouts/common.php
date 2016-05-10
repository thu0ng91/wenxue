<!DOCTYPE html>
<html>  
    <head>    
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <meta name="renderer" content="webkit">
        <?php
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile(Yii::app()->baseUrl . '/jsCssSrc/coreCss/bootstrap-v3.3.4.css');
        $cs->registerCssFile(Yii::app()->baseUrl . '/jsCssSrc/coreCss/font-awesome-v4.4.0.css');
        $cs->registerCssFile(Yii::app()->baseUrl . '/common/admin/zmf.css');        
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile(Yii::app()->baseUrl . "/jsCssSrc/coreJs/bootstrap-v3.3.4.js", CClientScript::POS_HEAD);
        $cs->registerScriptFile(Yii::app()->baseUrl . "/jsCssSrc/js/web-zmf.js", CClientScript::POS_END);
        ?>  
        <link rel="shortcut icon" href="<?php echo zmf::config('baseurl');?>favicon.ico" type="image/x-icon" />
        <title>管理中心</title>     
    </head>
    <body>
        <?php echo $content; ?>
        <?php assets::jsConfig('web');?> 
    </body>
</html>