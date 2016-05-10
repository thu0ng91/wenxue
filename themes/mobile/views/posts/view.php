<?php 
$url=zmf::config('domain').Yii::app()->createUrl('posts/view',array('id'=>$info['id']));
$qrcode=  zmf::qrcode($url, 'posts', $info['id']);
?>
<div class="ui-container">
    <div class="module">
        <?php if(!empty($tags)){?>
        <div class="tags-container">
            <?php foreach($tags as $tag){?>
            <span><?php echo CHtml::link($tag['title'],array('index/index','tagid'=>$tag['id']));?></span>
            <?php }?>
        </div>
        <?php }?>        
        <h1 class="post-title"><?php echo $info['title'];?></h1>
        <div class="post-content">
            <?php echo $info['content'];?>
        </div>
    </div>    
    <?php if($info['lat']!='' && $info['long']!='' && $info['lat']!='0' && $info['long']!='0'){?>
    <div class="module-header">
        位置
    </div>
    <div class="module">
        <p>
            <img data-original="http://ditu.google.cn/maps/api/staticmap?center=<?php echo $info['lat'];?>,<?php echo $info['long'];?>&amp;zoom=<?php echo $info['mapZoom'];?>&amp;size=600x371&amp;markers=color:red%7Clabel:A%7C<?php echo $info['lat'];?>,<?php echo $info['long'];?>&amp;sensor=false&amp;key=<?php echo zmf::config('googleApiKey');?>" class="lazy" src="<?php echo zmf::lazyImg();?>" alt="<?php echo $info['title'];?>">
        </p>
    </div>
    <?php }?>    
    <?php if(!empty($relatePosts)){?>
    <div class="module-header">
        更多
    </div>    
    <ul class="ui-list ui-list-text ui-list-link">
    <?php foreach($relatePosts as $_post){?>
        <li data-href="<?php echo Yii::app()->createUrl('posts/view',array('id'=>$_post['id']));?>" class="ui-border-b">
            <p><?php echo $_post['title'];?></p>
        </li>
    <?php }?>
    </ul>
    <?php }?>
    <div class="module-header">
        评论
    </div>
    <div id="comments-posts-<?php echo $info['id'];?>-box" class="post-comments">
        <ul id="comments-posts-<?php echo $info['id'];?>" class="ui-list ui-list-text">
            <?php if(!empty($comments)){?>
            <?php foreach($comments as $comment){?>
            <?php $this->renderPartial('/posts/_comment',array('data'=>$comment));?>
            <?php }?>
            <?php }else{?>
            <p class="zmf-tips">暂无评论！</p>
            <?php }?>
        </ul>
        <?php if($loadMore){?>
        <div class="loading-holder"><a class="btn btn-default btn-block" action="get-contents" action-data="<?php echo $info['id'];?>" action-type="comments" action-target="comments-posts-<?php echo $info['id'];?>" href="javascript:;" action-page="2">加载更多</a></div>
        <?php }?>
    </div>
    <div id="add-comments" class="module add-comments">
    <?php $this->renderPartial('/posts/_addComment', array('keyid' => $info['id'], 'type' => 'posts')); ?>
    </div>
</div>