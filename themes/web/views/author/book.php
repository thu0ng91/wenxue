<?php

/**
 * @filename book.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-13  17:56:14 
 */
?>
<div class="author-content-holder">
    <div class="media">
        <div class="media-left">
            <img class="media-object" src="<?php echo $info['faceImg'];?>" alt="<?php echo $info['title'];?>">            
        </div>
        <div class="media-body">
            <h4><?php echo $info['title'];?></h4>
            <p class="help-block"><?php echo $info['desc'];?></p>
            <p class="help-block"><?php echo $info['content'];?></p>
        </div>
    </div>
    <div class="module-header">目录</div>
    <div class="module-body book-chapters">
        <div class="list-group">
        <?php foreach ($chapters as $chapter){?>
        <?php echo CHtml::link($chapter['title'],array('book/chapter','cid'=>$chapter['id']),array('class'=>'list-group-item'));?>
        <?php }?>
        </div>
    </div>
</div>