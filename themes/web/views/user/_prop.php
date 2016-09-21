<?php

/**
 * @filename _prop.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-9-20  15:31:21 
 */
?>
<div class="prop-item">
    <span class="fixed-badge">x<?php echo $data['num'];?></span>
    <?php echo $data['num']>0 ? CHtml::link('使用','javascript:;',array('action'=>'useProp','action-data'=>$passdata,'class'=>'btn btn-success btn-xs fixed-btn')) : CHtml::link('用罄','javascript:;',array('class'=>'btn btn-danger btn-xs fixed-btn disabled'));?>
    <img class="media-object lazy a108" src="<?php echo zmf::lazyImg(); ?>" data-original="<?php echo $data['faceUrl']; ?>" alt="<?php echo $data['title']; ?>">
    <span class="fixed-title ui-nowrap"><?php echo $data['title'];?></span>
</div>