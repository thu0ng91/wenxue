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
<div class="first-floor" id="reply-<?php echo $data['id'];?>">    
        <div class="post-content-item"><?php echo $data['content'];?></div>
        <div class="post-footer-nav">
            <div class="post-time">发布于<?php echo zmf::formatTime($data['cTime']);?></div>
            <div><?php echo CHtml::link('举报','javascript:;',array('action'=>'report','action-type'=>'postPosts','action-id'=>$data['id'],'action-title'=>$info['title']));?></div>
            <?php if($this->uid){?>
            <div class="dropdown">
                <a class="dropdown-toggle" href="javascript:;" id="dropdownMenu-<?php echo $data['id'];?>" data-toggle="dropdown" aria-expanded="true">操作<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu-<?php echo $data['id'];?>">
                    <?php if (ForumAdmins::checkForumPower($this->uid, $info['fid'], 'setThreadStatus', false)) {?>                    
                    <li role="presentation"><?php echo CHtml::link($info['top'] ? '已置顶' : '置顶','javascript:;',array('action'=>'setStatus','data-type'=>'postPosts','data-action'=>'top','data-id'=>$info['id'],'role'=>'menuitem'));?></li>
                    <li role="presentation"><?php echo CHtml::link($info['styleStatus']==Posts::STATUS_BOLD ? '已加粗' : '加粗','javascript:;',array('action'=>'setStatus','data-type'=>'postPosts','data-action'=>'bold','data-id'=>$info['id'],'role'=>'menuitem'));?></li>
                    <li role="presentation"><?php echo CHtml::link($info['styleStatus']==Posts::STATUS_RED ? '已标红' : '标红','javascript:;',array('action'=>'setStatus','data-type'=>'postPosts','data-action'=>'red','data-id'=>$info['id'],'role'=>'menuitem'));?></li>
                    <li role="presentation"><?php echo CHtml::link($info['styleStatus']==Posts::STATUS_BOLDRED ? '已加粗标红' : '加粗标红','javascript:;',array('action'=>'setStatus','data-type'=>'postPosts','data-action'=>'boldAndRed','data-id'=>$info['id'],'role'=>'menuitem'));?></li>
                    <li role="presentation"><?php echo CHtml::link($info['open']==Posts::STATUS_OPEN ? '锁定' : '已锁定','javascript:;',array('action'=>'setStatus','data-type'=>'postPosts','data-action'=>'lock','data-id'=>$info['id'],'role'=>'menuitem'));?></li>
                    <li role="presentation" class="divider"></li>
                    <li role="presentation"><?php echo CHtml::link('移动','javascript:;',array('action'=>'changeForum','data-id'=>$info['id'],'role'=>'menuitem'));?></li>
                    <li role="presentation" class="divider"></li>
                    <?php }?>
                    
                    <?php if($data['uid']==$this->uid || $this->userInfo['isAdmin']){?>
                    <li role="presentation"><?php echo CHtml::link('编辑',array('posts/create','id'=>$info['id']));?></li>
                    <li role="presentation"><?php echo CHtml::link('删除','javascript:;',array('action'=>'delContent','data-type'=>'postPosts','data-id'=>$data['id'],'data-confirm'=>1,'data-redirect'=>Yii::app()->createUrl('posts/index',array('forum'=>$info['fid'])),'role'=>'menuitem'));?></li>
                    <?php }?>
                    
                </ul>
            </div>
            <?php }?>
            <div class="right-actions">
                <span><?php echo GroupPowers::link('favorPostReply',$this->userInfo,'<i class="fa '.($data['favorited'] ? 'fa-thumbs-up' : 'fa-thumbs-o-up').'"></i> '.$data['favors'],'javascript:;',array('action'=>'favorite','action-data'=>$data['id'],'action-type'=>'postPosts'),true);?></span>
                <span><?php echo CHtml::link('<i class="fa fa-comment-o"></i> '.$data['comments'],'javascript:;',array('action'=>'getContents','data-id'=>$data['id'],'data-type'=>'postPosts','data-target'=>'comments-postPosts-'.$data['id'],'data-loaded'=>0));?></span>
            </div>
        </div>
        <p class="text-center"><?php echo GroupPowers::link('rewardPostReply',$this->userInfo,'赞赏','javascript:;',array('action'=>'getProps','data-id'=>$data['id'],'data-type'=>'postPosts','data-target'=>'props-holder-'.$data['id'],'data-loaded'=>0,'class'=>'btn btn-xs btn-danger'));?></p>
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