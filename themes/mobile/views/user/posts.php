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
    <?php if(!empty($posts)){?>
        <?php foreach ($posts as $row): ?> 
        <p><?php echo CHtml::link($row['title'],array('posts/view','id'=>$row['id'])).($row['status']==Posts::STATUS_STAYCHECK ? '<font color="red">[待审核]</span>' : '');?></p>
        <?php endforeach; ?>
    <?php }?>        
    </div>
    <?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
</div>