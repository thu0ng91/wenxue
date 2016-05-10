<?php

/**
 * @filename one.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-14  15:31:36 
 */
$url=zmf::config('domain').Yii::app()->createUrl('zazhi/view',array('zid'=>$info['id']));
$qrcode=  zmf::qrcode($url, 'zazhi', $info['id']);
?>
<div class="zazhi-page">
    <div class="post-item">
        <?php if($info['faceimg']){?>
        <img data-original="<?php echo $info['faceimg'];?>" class="img-responsive lazy" src="<?php echo zmf::lazyImg();?>" alt="<?php echo $info['title'];?>的封面图"/>
        <?php }?>
        <div class="module">
            <h1 class="zazhi-title"><?php echo $info['title']; ?></h1>
            <p><?php echo nl2br($info['content']);?></p>
            <div class="post-item-footer">
                <div class="left-actions">
                    <span class="favor-num"><i class="fa fa-heart-o"></i> <?php echo $info['favorites'];?></span>
                    <span class="comment-num"><i class="fa fa-comment-o"></i> <?php echo $info['comments'];?></span>
                </div>
                <div class="dropdown right-actions">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-ellipsis-h"></i></a>  
                    <ul class="dropdown-menu">
                      <li><?php echo CHtml::link('分享','javascript:;',array('action'=>'share','action-qrcode'=>$qrcode,'action-url'=>$url,'action-img'=>$info['faceimg'],'action-title'=>$info['title']));?></li>
                      <?php if(($this->uid && $this->uid==$info['uid']) || $this->userInfo['isAdmin']){?>
                      <li role="separator" class="divider"></li>
                      <li><?php echo CHtml::link('编辑',array('admin/zazhi/update','id'=>$info['id']),array('target'=>'_blank'));?></li>                      
                      <?php }?>
                    </ul>
                  </div>
            </div>
        </div>
    </div>    
    <?php if(!empty($others))$this->renderPartial('/zazhi/_related',array('others'=>$others));?>
    <?php $this->renderPartial('/zazhi/_sidebar');?>    
</div>