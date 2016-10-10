<?php
/**
 * @filename _postPost.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-9-5  14:09:17 
 */
?>
<div class="media" id="reply-<?php echo $data['id'];?>">
    <div class="media-body">
        <div class="post-content-item"><?php echo $data['content'];?></div>
        <p>
            <span class="color-grey">
                发布于<?php echo zmf::formatTime($data['cTime']);?>
                
                <?php if($this->uid && $this->userInfo['isAdmin']){?>
                <span><?php echo CHtml::link($info['top'] ? '已置顶' : '置顶','javascript:;',array('action'=>'setStatus','data-type'=>'post','data-action'=>'top','data-id'=>$info['id']));?></span>
                <span><?php echo CHtml::link($info['styleStatus']==Posts::STATUS_BOLD ? '已加粗' : '加粗','javascript:;',array('action'=>'setStatus','data-type'=>'post','data-action'=>'bold','data-id'=>$info['id']));?></span>
                <span><?php echo CHtml::link($info['styleStatus']==Posts::STATUS_RED ? '已标红' : '标红','javascript:;',array('action'=>'setStatus','data-type'=>'post','data-action'=>'red','data-id'=>$info['id']));?></span>
                <span><?php echo CHtml::link($info['styleStatus']==Posts::STATUS_BOLDRED ? '已加粗标红' : '加粗标红','javascript:;',array('action'=>'setStatus','data-type'=>'post','data-action'=>'boldAndRed','data-id'=>$info['id']));?></span>
                <span>|</span>
                <span><?php echo CHtml::link($info['open']==Posts::STATUS_OPEN ? '锁定' : '已锁定','javascript:;',array('action'=>'setStatus','data-type'=>'post','data-action'=>'lock','data-id'=>$info['id']));?></span>
                
                <?php if($info['uid']!=$this->uid){?>
                <span><?php echo CHtml::link('编辑',array('posts/create','id'=>$info['id']));?></span>
                <span><?php echo CHtml::link('删除','javascript:;',array('action'=>'delContent','data-type'=>'post','data-id'=>$info['id'],'data-confirm'=>1,'data-redirect'=>Yii::app()->createUrl('posts/index',array('type'=>$type))));?></span>
                <?php }?>                        
                <?php }?>
                
                <?php if($info['uid']==$this->uid && $this->uid){?>
                <span><?php echo CHtml::link('编辑',array('posts/create','id'=>$info['id']));?></span>
                <span><?php echo CHtml::link('删除','javascript:;',array('action'=>'delContent','data-type'=>'post','data-id'=>$info['id'],'data-confirm'=>1,'data-redirect'=>Yii::app()->createUrl('posts/index',array('type'=>$type))));?></span>
                <?php }else{?>
                <span><?php echo CHtml::link('举报','javascript:;',array('action'=>'report','action-type'=>'post','action-id'=>$info['id'],'action-title'=>$info['title']));?></span>
                <?php }?>
            </span>
            <span class="right-actions">
                <span><?php echo GroupPowers::link('favorPostReply',$this->userInfo,'<i class="fa '.($data['favorited'] ? 'fa-thumbs-up' : 'fa-thumbs-o-up').'"></i> '.$data['favors'],'javascript:;',array('action'=>'favorite','action-data'=>$data['id'],'action-type'=>'postPosts'),true);?></span>
                <span><?php echo CHtml::link('<i class="fa fa-comment-o"></i> '.$data['comments'],'javascript:;',array('action'=>'getContents','data-id'=>$data['id'],'data-type'=>'postPosts','data-target'=>'comments-postPosts-'.$data['id'],'data-loaded'=>0));?></span>
            </span>
        </p>        
        <p class="text-center"><?php echo CHtml::link('赞赏'.$data['comments'],'javascript:;',array('action'=>'getProps','data-id'=>$data['id'],'data-type'=>'postPosts','data-target'=>'props-holder-'.$data['id'],'data-loaded'=>0,'class'=>'btn btn-xs btn-danger'));?></p>
        <?php $this->renderPartial('/common/props',array('props'=>$data['props'],'keyid'=>$data['id']));?>
        <div class="comments-list" id="comments-postPosts-<?php echo $data['id'];?>-box">
            <i class="icon-spike" style="display: inline;right:10px"></i>
            <div id="comments-postPosts-<?php echo $data['id'];?>"></div>
            <div id="comments-postPosts-<?php echo $data['id'];?>-form"></div>
        </div>
        <div class="props-holder" id="props-holder-<?php echo $data['id'];?>-box">
            <i class="icon-spike" style="display: inline;right:50%;margin-right: -6px"></i>
            <div id="props-holder-<?php echo $data['id'];?>"></div>
        </div>
    </div>
</div>