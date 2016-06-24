<?php

/**
 * @filename _user.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-30  14:09:43 
 */
?>
<div class="media ui-border-b">
    <div class="media-left">
        <a href="<?php echo Yii::app()->createUrl('user/index',array('id'=>$data['id']));?>" title="<?php echo $data['truename'];?>">
            <img class="media-object lazy a74 img-circle" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $data['avatar'];?>" alt="<?php echo $data['truename'];?>">
        </a>
    </div>
    <div class="media-body">
        <h4><?php echo CHtml::link($data['truename'],array('user/index','id'=>$data['id']));?></h4>        
        <p class="help-block"><?php echo zmf::subStr($data['content'],140);?></p>
    </div>
</div>