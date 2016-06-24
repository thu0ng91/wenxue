<?php

/**
 * @filename map.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-5  15:01:59 
 */
if($loadMap){
$this->renderPartial('/index/showMapinfo',array('postJson'=>$postJson));
?>
<script>
    $(document).ready(function() {
        showMap();
        loadScript();
    });
    $(window).resize(function() {
            showMap();
        });
    function showMap(){
        var w=$(window).width();
        var h=$(window).height();
        $('#map-canvas').css({
            width:w,
            height:h
        });
    }
</script>
<?php }else{?>
<div class="main-part" style="margin-top: 80px">
    <div class="module">
        <p><i class="fa fa-exclamation-circle"></i> 暂未发表包含位置的文章。<?php echo CHtml::link('点此返回','javascript:;',array('onclick'=>'history.back()'));?></p>
    </div>
</div>
    
<?php }