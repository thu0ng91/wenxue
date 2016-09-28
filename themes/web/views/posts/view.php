<?php 
$url=zmf::config('domain').Yii::app()->createUrl('posts/view',array('id'=>$info['id']));
$qrcode=  zmf::qrcode($url, 'posts', $info['id']);
?>
<div class="container post-page">    
    <div class="module">
        <div class="module-body">
            <div class="media">
                <div class="media-body">
                    <p><?php echo CHtml::link($forumInfo['title'],array('posts/index','forum'=>$forumInfo['id']));?><?php echo GroupPowers::link('favoriteForum',$this->userInfo,($_favorited ? '<i class="fa fa-check"></i> 已关注' : '<i class="fa fa-plus"></i> 关注'),'javascript:;',array('action'=>'favorite','action-data'=>$forumInfo['id'],'action-type'=>'forum'));?></p>
                </div>
                <div class="media-right">
                    <div class="" style="width: 80px">                        
                        <p><?php echo GroupPowers::link('addPost',$this->userInfo,'<i class="fa fa-plus"></i> 发表新帖',array('posts/create','forum'=>$forumInfo['id']),array('class'=>'btn btn-default btn-xs btn-block'));?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-part">
        <div class="module">
            <div class="module-body">
                <div class="media post-header">
                    <div class="media-body">
                        <h1><?php echo $info['title'];?></h1>
<!--                        <p class="color-grey tips">
                            <?php if($info['top']){?>
                            <span style="color:red" title="置顶"><i class="fa fa-bookmark"></i></span>
                            <?php }?>
                            <?php if($info['styleStatus']){?>
                            <span style="color:red" title="加精"><i class="fa fa-flag"></i></span>
                            <?php }?>
                        
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
                        </p>-->
                    </div>                    
                    <div class="media-right">
                        <p class="post-title-right">
                            <?php echo CHtml::link('只看楼主',array('posts/view','id'=>$info['id'],'see_lz'=>1),array('class'=>'btn btn-xs btn-default'));?>
                            <?php echo GroupPowers::link('favoritePost',$this->userInfo,'收藏','javascript:;',array('class'=>'btn btn-xs btn-default'));?>
                            <?php echo GroupPowers::link('addPostReply',$this->userInfo,'回复',array('posts/reply','tid'=>$info['id']),array('class'=>'btn btn-xs btn-default'));?>
                        </p>
                    </div>
                </div>                
                <div class="first-blood post-content">
                    <?php $this->renderPartial('/posts/_firstPost',array('data'=>$info['content']));?>
                </div>
                <?php if($info['open']==Posts::STATUS_OPEN){?>
                    <?php if($this->favorited){?>
                    <p class="text-center"><?php echo CHtml::link('<i class="fa fa-thumbs-up"></i> 已赞','javascript:;',array('class'=>'btn btn-default btn-sm','action'=>'favorite','action-data'=>$info['id'],'action-type'=>'post'));?></p>
                    <?php }else{?>
                    <p class="text-center"><?php echo CHtml::link('<i class="fa fa-thumbs-o-up"></i> 赞','javascript:;',array('class'=>'btn btn-danger btn-sm','action'=>'favorite','action-data'=>$info['id'],'action-type'=>'post'));?></p>
                    <?php }?>
                <?php }?> 
            </div>
        </div>
        <div class="module">
            <?php if(!empty($posts)){?>
            <div class="module-header">最新跟帖</div>            
            <div class="module-body post-content">                
                <?php foreach ($posts as $post){?>
                <?php $this->renderPartial('/posts/_postPost',array('data'=>$post));?>                    
                <?php }?>                
            </div>
            <?php }?>
            <div class="module-header">快速回复</div>
            <div class="module-body fast-reply">
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'fast-reply-form',
                    'action'=>  Yii::app()->createUrl('posts/reply',array('tid'=>$info['id'])),
                    'enableAjaxValidation'=>false,
                )); ?>
                <div class="media">
                    <div class="media-left">
                        <?php echo CHtml::link(CHtml::image(zmf::lazyImg(), $this->userInfo['truename'], array('data-original' => zmf::getThumbnailUrl($this->userInfo['avatar'],'a120','user'), 'class' => 'lazy img-circle a48')), array('user/index','id'=>$this->userInfo['id'])); ?>
                    </div>                    
                    <div class="media-body form-group">
                        <?php echo $form->textArea($model,'content',array('class'=>'form-control','rows'=>3,'placeholder'=>'说说你的看法'));?>
                    </div>
                    <div class="media-right">
                        <?php echo CHtml::submitButton($model->isNewRecord ? '回帖' : '更新',array('class'=>'btn btn-success','id'=>'add-post-btn')); ?>
                        <?php echo GroupPowers::link('addPostReply',$this->userInfo,'高级回复',array('posts/reply','tid'=>$info['id']),array('target'=>'_blank'));?>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
            
        </div>
    </div>
    <div class="aside-part">
        <div class="module">
            <div class="module-body post-side-authorInfo">
                <?php echo CHtml::link(CHtml::image(zmf::lazyImg(), $authorInfo['truename'], array('data-original' => $authorInfo['avatar'], 'class' => 'lazy img-circle a108')), array('user/index','id'=>$authorInfo['id'])); ?>
                <p class="title"><?php echo CHtml::link($authorInfo['truename'],array('user/index','id'=>$authorInfo['id'])); ?></p>
                <p class="color-grey"><?php echo CHtml::link($authorInfo['levelTitle'],array('site/level','id'=>$authorInfo['level'])); ?></p>
                <ul class="color-grey">
                    <li><?php echo $authorInfo['exp'];?><br/>经验</li>
                    <li><?php echo $authorInfo['favors'];?><br/>粉丝</li>
                    <li><?php echo $authorInfo['favord'];?><br/>关注</li>
                </ul>
                <p>
                   <?php echo CHtml::link('TA的主页',array('user/index','id'=>$authorInfo['id']),array('class'=>'btn btn-xs btn-success')); ?> 
                   <?php echo CHtml::link('关注TA',array('user/index','id'=>$authorInfo['id']),array('class'=>'btn btn-xs btn-danger')); ?> 
                </p>
            </div>
            <?php if(!empty($relatePosts)){?>
            <div class="module-header">最近发表</div>
            <div class="module-body">
                <?php foreach ($relatePosts as $relatePost){?>
                <p class="ui-nowrap"><?php echo CHtml::link($relatePost['title'],array('posts/view','id'=>$relatePost['id']));?></p>
                <?php }?>
            </div>
            <?php }?>
        </div>
        <?php if(!empty($topsPosts)){?>
        <div class="module">
            <div class="module-header">版块热门</div>
            <div class="module-body">
                <?php foreach ($topsPosts as $topsPost){?>
                <p class="ui-nowrap"><?php echo CHtml::link($topsPost['title'],array('posts/view','id'=>$topsPost['id']));?></p>
                <?php }?>
            </div>
        </div>
        <?php }?>
        <div class="module">
            <div class="module-header">分享</div>
            <div class="module-body">
                <?php $this->renderPartial('/common/share');?>
            </div>
        </div>        
    </div>
</div>