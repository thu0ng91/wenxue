<?php

/**
 * @filename posts.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-20  11:41:05 
 */
?>
<div class="main-part">
    <div class="module">
        <div class="module-header">他的帖子</div>
        <div class="module-body">
            <?php if(!empty($posts)){foreach ($posts as $k=>$_post){$this->renderPartial('/posts/_item',array('data'=>$_post,'posts'=>$posts,'k'=>$k));}?>
            <?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
            <?php }else{?>
            <p class="help-block text-center">暂无帖子</p>
            <?php }?>
        </div>    
    </div>    
</div>