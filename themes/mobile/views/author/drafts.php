<?php

/**
 * @filename drafts.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-25  17:14:26 
 */
?>
<div class="module-header">草稿箱</div>
<div class="module-body">
    <?php foreach ($posts as $post){?>
    <div class="media">
        <div class="media-body">
            <p class="title"><?php echo CHtml::link($post['title'],array('author/addChapter','bid'=>$post['bid'],'draft'=>$post['uuid']),array('target'=>'_blank'));?></p>
            <p><?php echo zmf::subStr($post['content'],140);?></p>
            <p class="color-grey">
                所属：<?php echo CHtml::link($post['bookTitle'],array('author/book','bid'=>$post['bid']));?>
                <span class="pull-right"><?php echo zmf::formatTime($post['cTime']);?></span>
            </p>
        </div>
    </div>
    <?php }?>
</div>