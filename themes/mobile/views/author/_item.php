<?php

/**
 * @filename _item.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-30  13:52:17 
 */
?>
<div class="media ui-border-b">
    <div class="media-left">
        <a href="<?php echo Yii::app()->createUrl('author/view',array('id'=>$data['id']));?>" title="<?php echo $data['authorName'];?>">
            <img class="media-object lazy w78" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $data['avatar'];?>" alt="<?php echo $data['authorName'];?>">
        </a>
    </div>
    <div class="media-body">
        <h4><?php echo CHtml::link($data['authorName'],array('author/view','id'=>$data['id']));?></h4>        
        <p class="help-block ui-nowrap-multi"><?php echo zmf::subStr($data['content'],140);?></p>
    </div>
</div>