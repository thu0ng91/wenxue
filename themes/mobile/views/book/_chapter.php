<?php

/**
 * @filename _chapter.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-30  14:21:34 
 */
$url=Yii::app()->createUrl('book/view',array('id'=>$data['bid']));
?>
<li class="ui-border-t" data-href="<?php echo $url;?>">
    <div class="ui-list-img">
        <img class="lazy w78" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $data['faceImg'];?>" alt="<?php echo $data['bTitle'];?>">        
    </div>
    <div class="ui-list-info">
        <h4 class="ui-nowrap"><?php echo $data['chapterTitle'];?></h4>
        <p>所属：<?php echo $data['bTitle'];?></p>
        <p class="help-block ui-nowrap-multi"><?php echo $data['desc'];?></p>
    </div>
</li>