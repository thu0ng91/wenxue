<?php

/**
 * @filename props.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-9-20  15:26:15 
 */
?>
<div class="main-part">
    <div class="module">
        <div class="module-header">我的背包</div>
        <div class="module-body props-holder" id="props-holder-box">
            <div id="props-holder">
                <?php echo CHtml::link('','javascript:;',array('action'=>'getProps','data-id'=>$this->userInfo['id'],'data-type'=>'user','data-target'=>'props-holder','data-loaded'=>0));?>
            </div>
        </div>    
    </div>    
</div>
<script>
    $(document).ready(function(){
        $("a[action=getProps]").click();
    })
</script>