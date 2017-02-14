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
    <?php if(!$data['anonymous']){?>
    <div class="media-left text-center">
        <?php echo CHtml::link(CHtml::image(zmf::lazyImg(), $data['userInfo']['username'], array('data-original' => $data['userInfo']['avatar'], 'class' => 'lazy img-circle a48 media-object')), $data['userInfo']['linkArr']); ?> 
        <?php if($data['userInfo']['levelTitle']){?>
        <p><?php echo CHtml::link($data['userInfo']['levelTitle'],'javascript:;',array('class'=>'btn btn-block btn-xs btn-default'));?></p>
        <?php }?>
    </div>
    <?php }else{?>
    <div class="media-left text-center">
        <img src="<?php echo zmf::config('baseurl').'common/images/default.png';?>" class="img-circle a48 media-object" alt="匿名者"/>
    </div>
    <?php }?>
    <div class="media-body">
        <p class="help-block title"><?php echo !$data['anonymous'] ? CHtml::link($data['userInfo']['username'],$data['userInfo']['linkArr']).($data['userInfo']['type']=='user' ? '' : ' <i class="fa fa-user" title="作者"></i>') : '匿名者';?></p>
        <div class="post-content-item"><?php echo nl2br(CHtml::decode($data['content']));?></div>
        <div class="post-footer-nav">
            <div class="post-time"><?php echo zmf::formatTime($data['cTime']);?></div>
            <div><?php echo CHtml::link('举报','javascript:;',array('action'=>'report','action-type'=>'postPosts','action-id'=>$data['id'],'action-title'=>  zmf::subStr($data['content'],8)));?></div>
            <?php if($data['uid']==$this->uid || $this->userInfo['isAdmin']){?>
            <div><?php echo CHtml::link('编辑',array('posts/reply','tid'=>$data['tid'],'pid'=>$data['id']));?></div>
            <div><?php echo CHtml::link('删除','javascript:;',array('action'=>'delContent','data-type'=>'postPosts','data-id'=>$data['id'],'data-confirm'=>1,'role'=>'menuitem'));?></div>
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