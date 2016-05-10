<?php

/**
 * @filename _sidebar.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-14  17:55:07 
 */
?>
<div id="chapters-box" class="chapters-box">
    <div class="list-group chapters">
        <?php echo CHtml::link('封面',array('zazhi/view','zid'=>$this->zazhiInfo['id']),array('class'=>'list-group-item'));?>
        <?php foreach ($this->chapters as $chap){?>
            <?php echo CHtml::link($chap['title'],$chap['url'],array('class'=>'list-group-item'));?>
         <?php }?>
    </div>   
</div>
<div class="next-prev-btns" id="next-prev-btns">
    <a href="javascript:;"  onclick="toggleChapters()"><div class="toggle-btn"><i class="fa fa-list"></i></div></a>    
    <?php if($this->currentPostId){?><div class="pre-btn"><?php echo CHtml::link('<i class="fa '.($this->favorited ? 'fa-heart' : 'fa-heart-o').'"></i>','javascript:;',array('action'=>'favorite','action-data'=>$this->currentPostId,'action-type'=>'post'));?></div><?php }?>
    <div class="pre-btn"><?php echo CHtml::link('<i class="fa fa-arrow-circle-left"></i>',!empty($this->prev) ? $this->prev['url'] : 'javascript:;',array('onclick'=>(!empty($this->prev) ? '' : 'simpleDialog({content:"已是第一篇"})')));?></div>
    <div class="next-btn"><?php echo CHtml::link('<i class="fa fa-arrow-circle-right"></i>',!empty($this->next) ? $this->next['url'] : 'javascript:;',array('onclick'=>(!empty($this->next) ? '' : 'simpleDialog({content:"已是最后一篇"})')));?></div>    
</div>
<script>      
 $(document).ready(function() {
    showChapters();
});
$(window).resize(function() {
    showChapters();        
});   
</script>