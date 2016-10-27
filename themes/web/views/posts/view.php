<?php 
$url=zmf::config('domain').Yii::app()->createUrl('posts/view',array('id'=>$info['id']));
$qrcode=  zmf::qrcode($url, 'posts', $info['id']);
?>
<div class="container post-page">    
    <div class="module">
        <div class="module-body">
            <div class="media">
                <div class="media-left">
                    <?php echo CHtml::link(CHtml::image(zmf::lazyImg(), $forumInfo['title'], array('data-original' => $forumInfo['faceImg'], 'class' => 'lazy img-circle a48 media-object')), array('posts/index','forum'=>$forumInfo['id'])); ?>
                </div>
                <div class="media-body">
                    <p class="title"><?php echo CHtml::link($forumInfo['title'],array('posts/index','forum'=>$forumInfo['id']));?></p>
                    <p class="color-grey"><?php echo $forumInfo['desc'];?></p>
                </div>
                <div class="media-right">
                    <div class="" style="width: 80px">
                        <p><?php echo !$favoritedForum ? GroupPowers::link('favoriteForum',$this->userInfo,'<i class="fa fa-plus"></i> 关注','javascript:;',array('class'=>'btn btn-'.'success'.' btn-xs btn-block','action'=>'favorite','action-data'=>$forumInfo['id'],'action-type'=>'forum')) : '';?></p>
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
                        <h1><?php if($info['top']){?><span style="color:red" title="置顶"><i class="fa fa-bookmark"></i></span><?php }?><?php if($info['styleStatus']){?><span style="color:red" title="加精"><i class="fa fa-flag"></i></span><?php }?><?php echo $info['title'];?></h1>
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
                    <?php $this->renderPartial('/posts/_firstPost',array('data'=>$info['content'],'info'=>$info));?>
                </div>
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
                <?php if($this->uid){?>
                <?php if(GroupPowers::checkAction($this->userInfo, 'addPostReply')){?>
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
                        <?php echo CHtml::link('高级回复',array('posts/reply','tid'=>$info['id']),array('target'=>'_blank'));?>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
                <?php }else{?>
                <p class="help-block">你所在用户组暂不能进行本操作。</p>
                <?php }?>
                <?php }else{?>
                <p class="help-block">登录后享有更多功能，<?php echo CHtml::link('立即登录',array('site/login'));?>或<?php echo CHtml::link('注册',array('site/reg'));?>。</p>
                <?php }?>
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
            <div class="module-header">「<?php echo $authorInfo['truename'];?>」近期发表</div>
            <div class="module-body">
                <?php foreach ($relatePosts as $relatePost){?>
                <p class="ui-nowrap"><?php echo CHtml::link($relatePost['title'],array('posts/view','id'=>$relatePost['id']));?></p>
                <?php }?>
            </div>
            <?php }?>
            <?php if(!empty($topsPosts)){?>        
            <div class="module-header">「<?php echo $forumInfo['title'];?>」近期热门</div>
            <div class="module-body">
                <?php foreach ($topsPosts as $topsPost){?>
                <p class="ui-nowrap"><?php echo CHtml::link($topsPost['title'],array('posts/view','id'=>$topsPost['id']));?></p>
                <?php }?>
            </div>        
            <?php }?>
        </div>        
        <div class="module">
            <div class="module-header">分享</div>
            <div class="module-body">
                <?php $this->renderPartial('/common/share');?>
            </div>
        </div>        
    </div>
</div>