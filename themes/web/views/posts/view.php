<?php 
$url=zmf::config('domain').Yii::app()->createUrl('posts/view',array('id'=>$info['id']));
$qrcode=  zmf::qrcode($url, 'posts', $info['id']);
?>
<div class="container post-page">    
    <ol class="breadcrumb">
        <li><?php echo CHtml::link(zmf::config('sitename').'首页',  zmf::config('baseurl'));?></li>
        <?php if($info['classify']==Posts::CLASSIFY_AUTHOR){?>
        <li><?php echo CHtml::link('作者专区',array('posts/index','type'=>'author'));?></li>
        <?php }elseif($info['classify']==Posts::CLASSIFY_READER){?>
        <li><?php echo CHtml::link('读者专区',array('posts/index','type'=>'reader'));?></li>
        <?php }?>
        <li class="active"><?php echo $info['title']; ?></li>
    </ol>
    <div class="main-part">
        <div class="module">
            <div class="module-body">
                <div class="media">
                    <div class="media-left">
                        <?php echo CHtml::link(CHtml::image(zmf::lazyImg(), $authorInfo['title'], array('data-original'=>$authorInfo['avatar'],'class'=>'lazy img-circle a64 media-object')),$authorInfo['url']);?>
                    </div>
                    <div class="media-body">
                        <h1><?php echo $info['title'];?></h1>
                        <p class="color-grey tips">
                            <?php if($info['top']){?>
                            <span style="color:red" title="置顶"><i class="fa fa-bookmark"></i></span>
                            <?php }?>
                            <?php if($info['styleStatus']){?>
                            <span style="color:red" title="加精"><i class="fa fa-flag"></i></span>
                            <?php }?>
                            <span><?php echo CHtml::link($authorInfo['title'],$authorInfo['url']);?></span>
                            <span><?php echo zmf::time($info['cTime'],'Y-m-d H:i');?></span>
                            <?php if($this->uid){?>
                            <span>|</span>
                            <?php }?>
                            <?php if($info['uid']==$this->uid && $this->uid){?>
                            <span><?php echo CHtml::link('编辑',array('posts/create','id'=>$info['id']));?></span>
                            <span><?php echo CHtml::link('删除','javascript:;',array('action'=>'delContent','data-type'=>'post','data-id'=>$info['id'],'data-confirm'=>1,'data-redirect'=>Yii::app()->createUrl('posts/index',array('type'=>$type))));?></span>
                            <?php }else{?>
                            <span><?php echo CHtml::link('举报','javascript:;',array('action'=>'report','action-type'=>'post','action-id'=>$info['id'],'action-title'=>$info['title']));?></span>
                            <?php }?>
                            <span class="pull-right">
                                <span title="点击"><i class="fa fa-eye"></i> <?php echo $info['hits'];?></span>
                                <span title="评论"><?php echo CHtml::link('<i class="fa fa-comments"></i> '.$info['comments'],'javascript:;',array('action'=>'scroll','action-target'=>'comments-posts-'.$info['id'].'-box'));?></span>
                                <span title="点赞"><i class="fa fa-thumbs-up"></i> <?php echo $info['favorite'];?></span>
                            </span>
                        </p>
                        <?php if($this->uid && $this->userInfo['isAdmin']){?>
                        <p class="color-grey tips">
                            <span>更多操作：</span>                            
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
                        </p>
                        <?php }?>
                    </div>
                </div>
                <div class="post-content">
                    <?php echo $info['content'];?>
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
        <?php if(!empty($comments) || $info['open']==Posts::STATUS_OPEN){?>
        <div class="module">
            <div class="module-header">评论</div>
            <div class="module-body">
                <div id="comments-posts-<?php echo $info['id'];?>-box" class="post-comments">
                    <div id="comments-posts-<?php echo $info['id'];?>">
                        <?php if(!empty($comments)){?>
                        <?php foreach($comments as $comment){?>
                        <?php $this->renderPartial('/posts/_comment',array('data'=>$comment,'postInfo'=>$info,'showAvatar'=>true));?>
                        <?php }?>
                        <?php }else{?>
                        <p class="help-block text-center">暂无评论！</p>
                        <?php }?>
                        <?php if($loadMore){?>
                        <div class="loading-holder"><a class="btn btn-default btn-sm text-center" action="getContents" data-id="<?php echo $info['id'];?>" data-type="postComments" data-target="comments-posts-<?php echo $info['id'];?>" href="javascript:;" data-page="2">加载更多</a></div>
                        <?php }?>
                    </div>
                    
                </div>
                <?php if($info['open']==Posts::STATUS_OPEN){?>
                <div id="add-comments">
                    <?php $this->renderPartial('/posts/_addComment', array('keyid' => $info['id'], 'type' => 'posts','authorPanel'=>($info['classify']==Posts::CLASSIFY_AUTHOR && $this->userInfo['authorId']>0),'authorLogin'=>  Authors::checkLogin($this->userInfo, $this->userInfo['authorId']))); ?>
                </div>
                <?php }?>
            </div>
        </div>
        <?php }?>
    </div>
    <div class="aside-part">
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