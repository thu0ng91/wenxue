<div class="zazhi-page">
    <?php if($info['isFaceimg']){?>
    <div class="post-item">
        <?php echo $info['content'];?>
    </div>
    <?php }else{?>
    <div class="module">
        <h1 class="zazhi-title"><?php echo $info['title'];?></h1>
        <p class="help-block text-center">评论：<?php echo $info['comments'];?> / 点赞：<?php echo $info['favorite'];?></p>
        <div class="post-content">
            <?php echo $info['content'];?>
        </div>
    </div>
    <?php }?>
    <?php $this->renderPartial('/zazhi/_sidebar',array('postInfo'=>$info));?>
    <?php if(!empty($others))$this->renderPartial('/zazhi/_related',array('others'=>$others));?>
    <div class="module">
        <div id="comments-posts-<?php echo $info['id'];?>-box" class="post-comments">
            <div id="comments-posts-<?php echo $info['id'];?>">
                <?php if(!empty($comments)){?>
                <?php foreach($comments as $comment){?>
                <?php $this->renderPartial('/zazhi/_comment',array('data'=>$comment,'postInfo'=>$info));?>
                <?php }?>
                <?php }else{?>
                <p class="help-block text-center">暂无评论！</p>
                <?php }?>
            </div>
            <?php if($loadMore){?>
            <div class="loading-holder"><a class="btn btn-default btn-block" action="get-contents" action-data="<?php echo $info['id'];?>" action-type="comments" action-target="comments-posts-<?php echo $info['id'];?>" href="javascript:;" action-page="2">加载更多</a></div>
            <?php }?>
        </div>
        <div style="margin-top: 15px"></div>
        <div id="add-comments">
        <?php $this->renderPartial('/zazhi/_addComment', array('keyid' => $info['id'], 'type' => 'posts')); ?>
        </div>
    </div>
</div>