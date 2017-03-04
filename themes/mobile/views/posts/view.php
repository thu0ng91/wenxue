<?php 
$url=zmf::config('domain').Yii::app()->createUrl('posts/view',array('id'=>$info['id']));
$qrcode=  zmf::qrcode($url, 'posts', $info['id']);
?>
<div class="post-page">
    <div class="module">
        <div class="module-body">
            <ul class="ui-list ui-list-function">
                <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('posts/index',array('forum'=>$forumInfo['id']));?>">
                    <div class="ui-avatar a50">
                        <img class="lazy a50" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $forumInfo['faceImg'];?>" alt="<?php echo $forumInfo['title'];?>">
                    </div>
                    <div class="ui-list-info">
                        <p class="ui-nowrap title"><?php echo $forumInfo['title'];?></p>
                        <p class="ui-nowrap-multi color-grey"><?php echo $forumInfo['desc'];?></p>
                    </div>
                    <?php echo GroupPowers::link('addPost',$this->userInfo,'发表新帖',array('posts/create','forum'=>$forumInfo['id']),array('class'=>'ui-btn ui-btn-primary'));?>
                </li>
            </ul>
        </div>
    </div>
    <div class="module">
        <div class="module-body">          
            <h1><?php echo $info['title'];?></h1>
            <div class="author-info">
                <?php echo CHtml::link($authorInfo['truename'],array('user/index','id'=>$authorInfo['id']));?>
                <span><?php echo zmf::formatTime($info['cTime']);?></span>
                <span class="stat-info">
                    <span><i class="fa fa-eye" title="访问"></i> <?php echo $info['hits'];?></span>
                    <span><i class="fa fa-comment"></i> <?php echo $info['posts'];?></span>
                </span>
            </div>
            <div class="post-content">
                <?php echo $info['content']['content'];?>
            </div>
            <?php if(!empty($info['content']['props'])){$props=$info['content']['props'];?>
            <div class="post-props" id="post-props-<?php echo $info['id'];?>">
                <div class="more-awesome"><span>赞赏榜</span></div>
                <ul class="ui-list ui-list-one">
                <?php foreach($props as $prop){?>   
                    <li class="ui-border-b"><div class="ui-list-info ui-border-t"><h4 class="ui-nowrap"><?php echo CHtml::link($prop['truename'],array('user/index','id'=>$prop['uid']));?></h4><div class="ui-txt-info"><?php echo $prop['title'];?> x<?php echo $prop['num'];?></div></div></li>
                <?php }?>  
                </ul>
            </div>
            <?php }?>
        </div>
    </div>
    <div class="module">
        <div class="module-header">最新回复</div>
        <?php if(!empty($posts)){?>
        <div class="module-body post-replys">            
            <?php foreach ($posts as $post){?>
            <?php $this->renderPartial('/posts/_postPost',array('data'=>$post));?>                    
            <?php }?> 
            <?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>  
        </div>
        <?php }else{?>
        <div class="module-body padding-body"><p class="help-block">还没有人回复，快来抢沙发。</p></div>
        <?php }?>
    </div>
    <div class="module">
        <div class="module-header">快速回复</div>
        <div class="module-body padding-body">
        <?php if($this->uid){?>
            <?php if($replyPostOrNot){if(GroupPowers::checkAction($this->userInfo, 'addPostReply')){?>
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'fast-reply-form',
                'action'=>  Yii::app()->createUrl('posts/reply',array('tid'=>$info['id'])),
                'enableAjaxValidation'=>false,
            )); ?>                                         
            <div class="form-group">
                <?php echo $form->textArea($model,'content',array('class'=>'form-control','rows'=>3,'placeholder'=>'说说你的看法'));?>
                <?php if($this->userInfo['authorId']>0){?>
                <div class="checkbox">
                    <label><?php echo $form->checkbox($model,'aid');?> 以作者身份回复</label>
                </div>
                <?php }?>   
                <?php if($forumInfo['anonymous']){?>
                <div class="checkbox">
                    <label><?php echo $form->checkbox($model,'anonymous');?> 匿名回复</label>
                </div>
                <?php }?>   
            </div>
            <div class="form-group">
                <?php echo CHtml::submitButton($model->isNewRecord ? '回帖' : '更新',array('class'=>'btn btn-success','id'=>'add-post-btn')); ?>
            </div>
            <?php $this->endWidget(); ?>
            <?php }else{?>
            <p class="help-block">你所在用户组暂不能进行本操作。</p>
            <?php }}else{?>
            <p class="help-block"><?php echo $replyPostOrNotLabel;?></p>
            <?php }?>
        <?php }else{?>
            <p class="help-block">登录后享有更多功能，<?php echo CHtml::link('立即登录',array('site/login'));?>或<?php echo CHtml::link('注册',array('site/reg'));?>。</p>
        <?php }?>
        </div>
    </div>
    <!--相关文章-->
    <?php if(!empty($relatePosts)){?>
    <div class="module">
        <div class="module-header">「<?php echo $authorInfo['title'];?>」近期发表</div>
        <div class="module-body padding-body">            
            <?php foreach ($relatePosts as $relatePost){?>                
                <p class="ui-nowrap"><?php echo CHtml::link($relatePost['title'],array('posts/view','id'=>$relatePost['id']));?></p>                       
            <?php }?>            
        </div>
    </div>
    <?php }?>
    <?php if(!empty($topsPosts)){?>
    <div class="module">
        <div class="module-header">「<?php echo $forumInfo['title'];?>」近期热门</div>
        <div class="module-body padding-body">            
            <?php foreach ($topsPosts as $topsPost){?>                
                <p class="ui-nowrap"><?php echo CHtml::link($topsPost['title'],array('posts/view','id'=>$topsPost['id']));?></p>                       
            <?php }?>            
        </div>
    </div>
    <?php }?>
</div>