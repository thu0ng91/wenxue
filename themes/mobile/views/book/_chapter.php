<?php

/**
 * @filename _chapter.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-30  14:21:34 
 */
?>
<div class="media ui-border-b">
    <div class="media-left">
        <a href="<?php echo Yii::app()->createUrl('book/view',array('id'=>$data['bid']));?>" title="<?php echo $data['bTitle'];?>">
            <img class="media-object lazy w78" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $data['faceImg'];?>" alt="<?php echo $data['bTitle'];?>">
        </a>
    </div>
    <div class="media-body">
        <h4><?php echo CHtml::link($data['chapterTitle'],array('book/chapter','cid'=>$data['cid']));?></h4>
        <p>所属：<?php echo CHtml::link($data['bTitle'],array('book/view','id'=>$data['bid']));?></p>
        <p class="help-block"><?php echo zmf::subStr($data['desc'],70);?></p>
    </div>
</div>