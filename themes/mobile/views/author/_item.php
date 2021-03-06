<?php

/**
 * @filename _item.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-30  13:52:17 
 */
$url=Yii::app()->createUrl('author/view',array('id'=>$data['id']));
?>
<li class="ui-border-t" data-href="<?php echo $url;?>">
    <div class="ui-list-img">
        <img class="lazy w78" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $data['avatar'];?>" alt="<?php echo $data['authorName'];?>">        
    </div>
    <div class="ui-list-info">
        <h4 class="ui-nowrap"><?php echo $data['authorName'];?></h4>        
        <p class="help-block ui-nowrap-multi"><?php echo $data['content'];?></p>
    </div>
</li>