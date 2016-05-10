<?php

/**
 * @filename _sidebar.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-14  17:55:07 
 */
if($this->currentPage!='faceimg'){
    $url=zmf::config('domain').Yii::app()->createUrl('zazhi/chapter',array('zid'=>$this->zazhiInfo['id'],'id'=>$postInfo['id']));
    $qrcode=  zmf::qrcode($url, 'posts', $postInfo['id']);
    $shareImg=$this->zazhiInfo['faceimg'];
    $shareTitle=$postInfo['title'];
}else{
    $url=zmf::config('domain').Yii::app()->createUrl('zazhi/view',array('zid'=>$this->zazhiInfo['id']));
    $qrcode=  zmf::qrcode($url, 'zazhi', $this->zazhiInfo['id']);
    $shareImg=$this->zazhiInfo['faceimg'];
    $shareTitle=$this->zazhiInfo['title'];
}
?>
<div class="post-fixed-actions" id="post-fixed-actions">
    <?php echo CHtml::link('<i class="fa fa-bookmark"></i>',array('zazhi/view','zid'=>$this->zazhiInfo['id']),array('title'=>'封面','data-toggle'=>'tooltip','data-placement'=>"left"));?>
    <?php echo CHtml::link('<i class="fa fa-list"></i>','javascript:;',array('title'=>'目录','data-toggle'=>'tooltip','data-placement'=>"left",'onclick'=>"toggleChapters()",'id'=>'toggle-chapters-btn'));?>    
    <?php echo CHtml::link('<i class="fa fa-arrow-circle-left"></i>',!empty($this->prev) ? $this->prev['url'] : 'javascript:;',array('action'=>'preChapter','data-toggle'=>'tooltip','data-placement'=>"left",'title'=>(!empty($this->prev) ? $this->prev['title'].'（键盘←）' : '已是第一篇'),'id'=>'preChapter'));?>
    <?php echo CHtml::link('<i class="fa fa-arrow-circle-right"></i>',!empty($this->next) ? $this->next['url'] : 'javascript:;',array('action'=>'nextChapter','data-toggle'=>'tooltip','data-placement'=>"left",'title'=>(!empty($this->next) ? $this->next['title'].'（键盘→）' : '已是最后一篇'),'id'=>'nextChapter'));?>
    
    <span class="divider"></span>
    <?php if($this->currentPage!='faceimg'){?>
    <?php echo CHtml::link('<i class="fa fa-heart-o"></i>','javascript:;',array('action'=>'favorite','action-data'=>$this->currentPostId,'action-type'=>'post','title'=>'点赞','data-toggle'=>'tooltip','data-placement'=>"left"));?>
    <p><?php echo CHtml::link('<i class="fa fa-comment-o"></i>','javascript:;',array('action'=>'scroll','action-target'=>'add-comments','title'=>'评论','data-toggle'=>'tooltip','data-placement'=>"left"));?></p>
    <?php }?>
    <p><?php echo CHtml::link('<i class="fa fa-qrcode"></i>','javascript:;',array('action'=>'share','action-qrcode'=>$qrcode,'action-url'=>$url,'action-img'=>$qrcode,'action-title'=>$shareTitle,'title'=>'分享','data-toggle'=>'tooltip','data-placement'=>"left"));?></p>
</div>
<div id="chapters-box" class="chapters-box">
    <div class="chapters-top-btns">
        <span><?php echo CHtml::link('<i class="fa fa-remove"></i>','javascript:;',array('onclick'=>'toggleChapters()'));?></span>
    </div>
    <div class="chapters-holder" id="chapters-holder">
        <div class="list-group">
            <?php foreach ($this->chapters as $chap){?>
            <?php echo CHtml::link($chap['title'],$chap['url'],array('class'=>'list-group-item'));?>
            <?php }?>
        </div>  
    </div>    
</div>
<script>
    $(document).ready(function() {
        showChapters();
    });   
</script>