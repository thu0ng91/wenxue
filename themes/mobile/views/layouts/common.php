<!DOCTYPE HTML>
<html>  
  <head>    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no,user-scalable=0">
    <meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,user-scalable=0">
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style"  />
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="full-screen" content="yes">
    <meta name="format-detection" content="telephone=no">    
    <meta name="format-detection" content="address=no">
    <meta name="keywords" content="<?php if (!empty($this->keywords)){echo $this->keywords;}else{ echo zmf::config('siteKeywords');}?>" />
    <meta name="description" content="<?php if (!empty($this->pageDescription)){echo $this->pageDescription;}else{ echo zmf::config('siteDesc');}?>" />
    <?php assets::loadCssJs($this->currentModule);?>  
    <link rel="shortcut icon" href="<?php echo zmf::config('baseurl');?>favicon.ico" type="image/x-icon" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>     
  </head>
  <body ontouchstart>
      <?php echo $content; ?>
      <?php assets::jsConfig('mobile',$this->currentModule);?> 
      <?php echo zmf::config('tongji');?> 
  </body>
</html>