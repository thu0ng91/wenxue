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
    <ul class="ui-list">
        <li class="ui-border-t">
            <div class="ui-avatar a36">
                <img class="lazy a36" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $data['userInfo']['avatar'];?>" alt="<?php echo $data['userInfo']['username'];?>">        
            </div>
            <div class="ui-list-info">
                <p class="ui-nowrap"><?php echo CHtml::link($data['userInfo']['username'],$data['userInfo']['linkArr']).($data['userInfo']['type']=='user' ? '' : ' <i class="fa fa-user" title="作者"></i>');?></p>
                <p class="color-grey">发表于<?php echo zmf::formatTime($data['cTime']);?></p>
            </div>
        </li>
    </ul>
    <div class="media-body">
        <div class="post-content"><?php echo $data['content'];?></div>
    </div>
</div>