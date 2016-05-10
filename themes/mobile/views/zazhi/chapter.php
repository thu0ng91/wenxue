<?php 
$url=zmf::config('domain').Yii::app()->createUrl('posts/view',array('id'=>$info['id']));
$qrcode=  zmf::qrcode($url, 'posts', $info['id']);
?>
<style>
    .lazy{
        min-height: 150px;
    }
</style>
<div class="main-part">
    <?php $this->renderPartial('/zazhi/_sidebar');?> 
    <?php if(!$info['isFaceimg']){?>
    <div class="module zazhi-page">        
        <h1 class="zazhi-title text-center"><?php echo $info['title'];?></h1>
        <p class="help-block text-center">评 <?php echo $info['comments'];?>&nbsp;&nbsp;赞 <?php echo $info['favorite'];?></p>
        <div class="post-content">
            <?php echo $info['content'];?>
        </div>
    </div>
    <?php }else{?>
    <?php echo $info['content'];?>
    <?php }?>
    <?php if(!empty($others))$this->renderPartial('/zazhi/_related',array('others'=>$others));?>
    <div class="module-header">评论</div>
    <div class="module">
        <div id="comments-posts-<?php echo $info['id'];?>-box" class="post-comments">
            <div id="comments-posts-<?php echo $info['id'];?>">
                <?php if(!empty($comments)){?>
                <?php foreach($comments as $comment){?>
                <?php $this->renderPartial('/zazhi/_comment',array('data'=>$comment));?>
                <?php }?>
                <?php }else{?>
                <div class="zmf-tips">暂无评论！</div>
                <?php }?>
            </div>
            <?php if($loadMore){?>
            <div class="loading-holder"><a class="btn btn-default btn-block" action="get-contents" action-data="<?php echo $info['id'];?>" action-type="comments" action-target="comments-posts-<?php echo $info['id'];?>" href="javascript:;" action-page="2">加载更多</a></div>
            <?php }?>        
        </div> 
        <div id="add-comments" class="add-comments">
            <?php $this->renderPartial('/zazhi/_addComment', array('keyid' => $info['id'], 'type' => 'posts')); ?>
        </div>
    </div>
</div>