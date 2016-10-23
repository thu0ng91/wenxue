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
    <div class="media-left text-center">
        <?php echo CHtml::link(CHtml::image(zmf::lazyImg(), $data['username'], array('data-original' => $data['avatar'], 'class' => 'lazy img-circle a48 media-object')), $data['authorUrl']); ?>        
        <?php if($data['levelTitle']){?>
        <p><?php echo CHtml::link($data['levelTitle'],array('site/level','id'=>$data['level']),array('class'=>'btn btn-block btn-xs btn-default'));?></p>
        <?php }?>
    </div>
    <div class="media-body">
        <p class="help-block title"><?php echo CHtml::link($data['username'],array());?></p>
        <div class="post-content-item"><?php echo $data['content'];?></div>
        <div class="post-footer-nav">
            <div class="post-time"><?php echo zmf::formatTime($data['cTime']);?></div>
            <div><?php echo CHtml::link('举报','javascript:;',array('action'=>'report','action-type'=>'post','action-id'=>$data['id'],'action-title'=>$data['username']));?></div>
            <?php if($data['uid']==$this->uid || $this->userInfo['isAdmin']){?>
            <div><?php echo CHtml::link('编辑',array('posts/reply','tid'=>$data['tid'],'pid'=>$data['id']));?></div>
            <div><?php echo CHtml::link('删除','javascript:;',array('action'=>'delContent','data-type'=>'post','data-id'=>$data['id'],'data-confirm'=>1,'role'=>'menuitem'));?></div>
            <?php }?>            
            <div class="right-actions">
                <span><?php echo GroupPowers::link('favorPostReply',$this->userInfo,'<i class="fa '.($data['favorited'] ? 'fa-thumbs-up' : 'fa-thumbs-o-up').'"></i> '.$data['favors'],'javascript:;',array('action'=>'favorite','action-data'=>$data['id'],'action-type'=>'postPosts'),true);?></span>
                <span><?php echo CHtml::link('<i class="fa fa-comment-o"></i> '.$data['comments'],'javascript:;',array('action'=>'getContents','data-id'=>$data['id'],'data-type'=>'postPosts','data-target'=>'comments-postPosts-'.$data['id'],'data-loaded'=>0));?></span>
            </div>
        </div>
        <div class="comments-list" id="comments-postPosts-<?php echo $data['id'];?>-box">
            <i class="icon-spike" style="display: inline;right:10px"></i>
            <div id="comments-postPosts-<?php echo $data['id'];?>"></div>
            <div id="comments-postPosts-<?php echo $data['id'];?>-form"></div>
        </div>
    </div>
</div>