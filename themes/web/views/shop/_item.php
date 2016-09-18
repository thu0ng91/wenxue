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
    <img src="<?php echo zmf::lazyImg();?>" class="lazy" data-original="<?php echo $data['faceUrl'];?>"/>
    <div class="caption">
        <p class="price"><span><i class="fa fa-rmb"></i><?php echo $data['scorePrice'];?></span><span><i class="fa fa-diamond"></i><?php echo $data['goldPrice'];?></span></p>
        <h3><?php echo CHtml::link($data['title'],array('shop/detail','id'=>$data['id']));?></h3>
        <p class="desc ui-nowrap-multi color-grey"><?php echo $data['desc'];?></p>
    </div>
</div>