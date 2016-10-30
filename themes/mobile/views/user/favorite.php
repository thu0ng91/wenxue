<?php

/**
 * @filename favorite.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-13  16:23:48 
 */
?>
<div class="module">
    <div class="module-header">收藏</div>
    <div class="module-body">
        <?php if(!empty($posts)){?>
        <ul class="ui-list ui-list-link ui-border-t">
            <?php foreach ($posts as $post){
            $this->renderPartial('/book/_item',array('data'=>$post,'adminLogin'=>false));
        }?>
        </ul>
        <?php }else{?>
        <p class="help-block text-center">暂无记录</p>
        <?php }?>
    </div>
</div>