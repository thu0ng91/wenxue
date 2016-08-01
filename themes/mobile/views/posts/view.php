<?php 
$url=zmf::config('domain').Yii::app()->createUrl('posts/view',array('id'=>$info['id']));
$qrcode=  zmf::qrcode($url, 'posts', $info['id']);
?>
<div class="post-page">
    <div class="module">
        <div class="module-body">
            <!--标签-->
            <?php if(!empty($tags)){?>
            <div class="post-tags">
                <?php foreach ($tags as $tag){?>
                <?php echo CHtml::link($tag['title'],array('posts/index','type'=>Posts::exType($info['classify']),'tagid'=>$tag['id']));?>
                <?php }?>
            </div>
            <?php }?>            
            <h1><?php echo $info['title'];?></h1>
            <ul class="ui-list author-info">
                <li class="ui-border-t">
                    <div class="ui-avatar">
                        <img class="lazy a50" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $authorInfo['avatar'];?>" alt="<?php echo $authorInfo['title'];?>">        
                    </div>
                    <div class="ui-list-info">
                        <h4 class="ui-nowrap"><?php echo CHtml::link($authorInfo['title'],$authorInfo['url']);?></h4>
                        <p class="ui-nowrap">
                            <?php echo zmf::formatTime($info['cTime']);?>
                        </p>
                    </div>
                </li>
            </ul>
            <div class="right-fixed-btns">
                <?php echo CHtml::link('<i class="fa fa-comment"></i><sup>'.$info['comments'].'</sup>','javascript:;',array('action'=>'scroll','action-target'=>'comments-posts-'.$info['id'].'-box'));?>
                <?php if($info['open']==Posts::STATUS_OPEN){?>
                    <?php if($this->favorited){?>
                    <?php echo CHtml::link('<i class="fa fa-thumbs-up"></i><sup>'.$info['favorite'].'</sup>','javascript:;',array('action'=>'favorite','action-data'=>$info['id'],'action-type'=>'post'));?>
                    <?php }else{?>
                    <?php echo CHtml::link('<i class="fa fa-thumbs-o-up"></i><sup>'.$info['favorite'].'</sup>','javascript:;',array('action'=>'favorite','action-data'=>$info['id'],'action-type'=>'post'));?>
                    <?php }?>
                <?php }?>                
            </div>
<!--            <p class="color-grey tips">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span>|</span>
                <?php if($info['uid']==$this->uid && $this->uid && !$this->userInfo['isAdmin']){?>
                <span><?php echo CHtml::link('编辑',array('posts/create','id'=>$info['id']));?></span>
                <span><?php echo CHtml::link('删除','javascript:;',array('action'=>'delContent','data-type'=>'post','data-id'=>$info['id'],'data-confirm'=>1,'data-redirect'=>Yii::app()->createUrl('posts/index',array('type'=>$type))));?></span>
                <?php }elseif($this->uid){?>
                <?php if($this->userInfo['isAdmin']){?>
                <span><?php echo CHtml::link($info['top'] ? '已置顶' : '置顶','javascript:;',array('action'=>'setStatus','data-type'=>'post','data-action'=>'top','data-id'=>$info['id']));?></span>
                <span><?php echo CHtml::link($info['styleStatus']==Posts::STATUS_BOLD ? '已加粗' : '加粗','javascript:;',array('action'=>'setStatus','data-type'=>'post','data-action'=>'bold','data-id'=>$info['id']));?></span>
                <span><?php echo CHtml::link($info['styleStatus']==Posts::STATUS_RED ? '已标红' : '标红','javascript:;',array('action'=>'setStatus','data-type'=>'post','data-action'=>'red','data-id'=>$info['id']));?></span>
                <span><?php echo CHtml::link($info['styleStatus']==Posts::STATUS_BOLDRED ? '已加粗标红' : '加粗标红','javascript:;',array('action'=>'setStatus','data-type'=>'post','data-action'=>'boldAndRed','data-id'=>$info['id']));?></span>
                <span>|</span>
                <span><?php echo CHtml::link($info['open']==Posts::STATUS_OPEN ? '锁定' : '已锁定','javascript:;',array('action'=>'setStatus','data-type'=>'post','data-action'=>'lock','data-id'=>$info['id']));?></span>
                <span><?php echo CHtml::link('编辑',array('posts/create','id'=>$info['id']));?></span>
                <span><?php echo CHtml::link('删除','javascript:;',array('action'=>'delContent','data-type'=>'post','data-id'=>$info['id'],'data-confirm'=>1,'data-redirect'=>Yii::app()->createUrl('posts/index',array('type'=>$type))));?></span>
                <?php }else{?>
                <span><?php echo CHtml::link('举报','javascript:;',array('action'=>'report','action-type'=>'post','action-id'=>$info['id'],'action-title'=>$info['title']));?></span>
                <?php }?>
                <?php }?>
            </p>-->
            <div class="post-content">
                <?php echo $info['content'];?>
            </div>
            
        </div>
    </div>    
    <!--相关文章-->
    <?php if(!empty($relatePosts)){?>
    <div class="module">
        <div class="module-header">相关文章</div>
        <div class="module-body padding-body">            
            <?php foreach ($relatePosts as $relatePost){?>                
                <p class="ui-nowrap"><?php echo CHtml::link($relatePost['title'],array('posts/view','id'=>$relatePost['id']));?></p>                       
            <?php }?>            
        </div>
    </div>
    <?php }?>
    <!--评论-->
    <?php if(!empty($comments) || $info['open']==Posts::STATUS_OPEN){?>
    <div class="module">
        <div class="module-header">评论</div>
        <div class="module-body">
            <div id="comments-posts-<?php echo $info['id'];?>-box" class="post-comments">
                <ul id="comments-posts-<?php echo $info['id'];?>" class="ui-list">
                    <?php if(!empty($comments)){?>
                    <?php foreach($comments as $comment){?>
                    <?php $this->renderPartial('/posts/_comment',array('data'=>$comment,'postInfo'=>$info));?>
                    <?php }?>
                    <?php }else{?>
                    <p class="help-block text-center">暂无评论！</p>
                    <?php }?>
                </ul>
                <?php if($loadMore){?>
                <div class="loading-holder"><a class="btn btn-default btn-block" action="get-contents" action-data="<?php echo $info['id'];?>" action-type="comments" action-target="comments-posts-<?php echo $info['id'];?>" href="javascript:;" action-page="2">加载更多</a></div>
                <?php }?>
            </div>
            <?php if($info['open']==Posts::STATUS_OPEN){?>
            <div id="add-comments" class="padding-body">
                <?php $this->renderPartial('/posts/_addComment', array('keyid' => $info['id'], 'type' => 'posts','authorPanel'=>($info['classify']==Posts::CLASSIFY_AUTHOR && $this->userInfo['authorId']>0),'authorLogin'=>  Authors::checkLogin($this->userInfo, $this->userInfo['authorId']))); ?>
            </div>
            <?php }?>
        </div>
    </div>
    <?php }?>
    
    
        
    
</div>