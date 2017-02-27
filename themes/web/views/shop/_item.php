<?php

/**
 * @filename _item.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-9-18  16:28:19 
 */
?>
<div class="thumbnail">
    <a href="<?php echo Yii::app()->createUrl('shop/detail',array('id'=>$data['id']));?>">
        <img src="<?php echo zmf::lazyImg();?>" class="lazy" data-original="<?php echo $data['faceUrl'];?>" alt="<?php echo $data['title'];?>"/>
    </a>
    <div class="caption">
        <p class="price">
            <?php if($data['scorePrice']!=0){?>
            <span><?php echo $data['scorePrice'];?>积分</span>
            <?php }if($data['goldPrice']!=0){?>
            <span><?php echo $data['goldPrice'];?>金币</span>
            <?php }?>
        </p>
        <h3><?php echo CHtml::link($data['title'],array('shop/detail','id'=>$data['id']));?></h3>
        <p class="desc ui-nowrap-multi color-grey"><?php echo $data['desc'];?></p>
    </div>
</div>