<?php 
$url=zmf::config('domain').Yii::app()->createUrl('posts/view',array('id'=>$info['id']));
$qrcode=  zmf::qrcode($url, 'posts', $info['id']);
?>
<style>
    .post-page .main-part .module .module-body{
        padding-left: 0;
        padding-right: 0
    }
    .post-header{
        border-bottom: 1px solid #e4e4e4;
        padding-left: 20px;
        padding-right: 20px;
        
    }
    .post-title-right{
        width: 140px;
        display: inline-block;
        text-align: right;
    }
    .post-content .media .media-left{
        padding-left: 20px;
    }
    .post-content .media .media-body{
        padding-left: 0;
        padding-right: 20px;
    }
</style>
<div class="container post-page">
    <ol class="breadcrumb">
        <li><?php echo CHtml::link(zmf::config('sitename').'首页',  zmf::config('baseurl'));?></li>        
        <li><?php echo CHtml::link($forumInfo['title'],array('posts/index','forum'=>$forumInfo['id']));?></li>        
        <li class="active"><?php echo $info['title']; ?></li>
    </ol>
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
                <div class="post-content">
                    <?php foreach ($posts as $post){?>
                    <?php $this->renderPartial('/posts/_postPost',array('data'=>$post));?>                    
                    <?php }?>
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
        
    </div>
    <div class="aside-part">
        <div class="module">
            <div class="module-header">分享</div>
            <div class="module-body">
                <?php $this->renderPartial('/common/share');?>
            </div>
        </div>
        
        <?php if(!empty($tags)){?>
        <div class="module">
            <div class="module-header">文章标签</div>
            <div class="module-body">
                <?php foreach ($tags as $tag){?>
                <p><?php echo CHtml::link('<i class="fa fa-tag"></i> '.$tag['title'],array('posts/index','type'=>Posts::exType($info['classify']),'tagid'=>$tag['id']));?></p>
                <?php }?>
            </div>
        </div>
        <?php }?>
        <?php if(!empty($relatePosts)){?>
        <div class="module">
            <div class="module-header">相关文章</div>
            <div class="module-body">
                <?php foreach ($relatePosts as $relatePost){?>
                <p class="ui-nowrap"><?php echo CHtml::link($relatePost['title'],array('posts/view','id'=>$relatePost['id']));?></p>
                <?php }?>
            </div>
        </div>
        <?php }?>
        <?php if(!empty($topsPosts)){?>
        <div class="module">
            <div class="module-header">热门文章</div>
            <div class="module-body">
                <?php foreach ($topsPosts as $topsPost){?>
                <p class="ui-nowrap"><?php echo CHtml::link($topsPost['title'],array('posts/view','id'=>$topsPost['id']));?></p>
                <?php }?>
            </div>
        </div>
        <?php }?>
    </div>
</div>