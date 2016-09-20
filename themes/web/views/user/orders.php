<?php

/**
 * @filename orders.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-9-20  14:57:07 
 */
?>
<div class="main-part">
    <div class="module">
        <div class="module-header">我的订单</div>
        <div class="module-body">
            <?php if(!empty($posts)){foreach ($posts as $_post){$this->renderPartial('/user/_order',array('data'=>$_post));}?>
            <?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
            <?php }else{?>
            <p class="help-block text-center">暂无兑换记录</p>
            <?php }?>
        </div>    
    </div>    
</div>