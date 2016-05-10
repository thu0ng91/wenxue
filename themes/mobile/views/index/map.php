<?php

/**
 * @filename map.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-5  15:01:59 
 */
?>


<?php $this->renderPartial('/index/showMapinfo',array('postJson'=>$postJson));?>
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
        var dpi = window.devicePixelRatio;//获取屏幕分辨率
        $('#map-canvas').css({
            width:w/ dpi,
            height:h/ dpi
        });
    }
</script>