<?php

/**
 * @filename view.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-15  15:01:16 
 */
?>
<section class="main-part">
    <?php $this->renderPartial('/zazhi/_sidebar');?> 
    <img src="<?php echo $info['faceimg'];?>" class="img-responsive"/>
    <div class="module zazhi-page">
        <h1 class="zazhi-title"><?php echo $info['title'];?></h1>
        <p><?php echo nl2br($info['content']);?></p>
        <div class="post-item-footer">
            <div class="left-actions">
                <span class="favor-num"><i class="fa fa-heart-o"></i> <?php echo $info['favorites'];?></span>
                <span class="comment-num"><i class="fa fa-comment-o"></i> <?php echo $info['comments'];?></span>
            </div>
        </div>
    </div>    
    <?php if(!empty($others))$this->renderPartial('/zazhi/_related',array('others'=>$others));?>    
</section>