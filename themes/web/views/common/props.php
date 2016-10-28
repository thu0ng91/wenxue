<?php

/**
 * @filename props.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-9-22  15:16:20 
 */
?>
<?php if(!empty($props)){?>
<div class="post-props" id="post-props-<?php echo $keyid;?>">    
    <?php foreach($props as $prop){?>            
    <p class="ui-border-b color-grey">
        <span><?php echo CHtml::link($prop['truename'],array('user/index','id'=>$prop['uid']));?></span>
        <span class="text-right"><?php echo $prop['title'];?> x<?php echo $prop['num'];?></span>
        <span class="text-right"><?php echo zmf::formatTime($prop['updateTime']);?></span>
    </p>
    <?php }?>    
</div>
<?php }?>