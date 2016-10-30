<?php

/**
 * @filename comment.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-13  16:23:02 
 */
?>
<div class="module">
    <div class="module-header">点评</div>
    <div class="module-body" id="more-content">        
        <?php if(!empty($tips)){?>
        <?php foreach ($tips as $tip){?> 
        <?php $this->renderPartial('/book/_tip',array('data'=>$tip));?>
        <?php }?>
        <?php }else{?>
        <p class="color-grey text-center">他很懒，还没点评过！</p>
        <?php }?>        
    </div>
</div>