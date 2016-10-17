<?php

/**
 * @filename _postPost.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-10-17  10:53:31 
 */
?>
<div class="media" id="reply-<?php echo $data['id'];?>">
    <ul class="ui-list author-info">
        <li class="ui-border-t">
            <div class="ui-avatar a36">
                <img class="lazy a36" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $data['avatar'];?>" alt="<?php echo $data['username'];?>">        
            </div>
            <div class="ui-list-info">
                <h4 class="ui-nowrap"><?php echo CHtml::link($data['username'],$data['authorUrl']);?></h4>
                <p class="color-grey"><?php echo zmf::formatTime($data['cTime']);?></p>
            </div>
        </li>
    </ul>
    <div class="media-body">
        <div class="post-content"><?php echo $data['content'];?></div>
    </div>
</div>