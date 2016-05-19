<?php 
$url=zmf::config('domain').Yii::app()->createUrl('posts/view',array('id'=>$info['id']));
$qrcode=  zmf::qrcode($url, 'posts', $info['id']);
?>
<style>
    .post-page .aside-part{
        width: 300px;
    }
    .post-page .module{
        padding: 0
    }
    .post-page h1{
        font-size: 24px;
        padding: 0;
        margin-bottom: 10px;
        margin-top: 0
    }
    .post-page .tips{
        margin-bottom: 20px
    }
    .post-page .tips span{
        margin-right: 5px;
    }
</style>
<div class="container post-page">    
    <ol class="breadcrumb">
        <li><a href="#">初心创文首页</a></li>
        <li><a href="#">作者专区</a></li>
        <li class="active"><?php echo $info['title']; ?></li>
    </ol>
    <div class="main-part">
        <div class="module">
            <div class="module-body">
                <h1><?php echo $info['title'];?></h1>
                <p class="color-grey tips">
                    <span><?php echo zmf::time($info['cTime'],'Y-m-d H:i');?></span>
                    <span>大飞</span>                
                </p>
                <div class="post-content">
                    <?php echo $info['content'];?>
                </div>
                <p class="text-center"><?php echo CHtml::link('<i class="fa fa-thumbs-o-up"></i> 赞','javascript:;',array('class'=>'btn btn-default btn-small'));?></p>
            </div>
        </div>
        <div class="module">
            <div class="module-header">评论</div>
            <div class="module-body">
                <div id="comments-posts-<?php echo $info['id'];?>-box" class="post-comments">
                    <div id="comments-posts-<?php echo $info['id'];?>">
                        <?php if(!empty($comments)){?>
                        <?php foreach($comments as $comment){?>
                        <?php $this->renderPartial('/posts/_comment',array('data'=>$comment,'postInfo'=>$info));?>
                        <?php }?>
                        <?php }else{?>
                        <p class="help-block text-center">暂无评论！</p>
                        <?php }?>
                    </div>
                    <?php if($loadMore){?>
                    <div class="loading-holder"><a class="btn btn-default btn-block" action="get-contents" action-data="<?php echo $info['id'];?>" action-type="comments" action-target="comments-posts-<?php echo $info['id'];?>" href="javascript:;" action-page="2">加载更多</a></div>
                    <?php }?>
                </div>
                <div id="add-comments">
                    <?php $this->renderPartial('/posts/_addComment', array('keyid' => $info['id'], 'type' => 'posts')); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="aside-part">
        <div class="module">
            <div class="module-header">相关文章</div>
            <div class="module-body">
                
            </div>
        </div>
        <?php $this->renderPartial('/common/copyright');?>
    </div>
</div>