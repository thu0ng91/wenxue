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
                        <h4 class="ui-nowrap"><?php echo CHtml::link($authorInfo['truename'],array('user/index','id'=>$authorInfo['id']));?></h4>
                        <p class="ui-nowrap">
                            <?php echo zmf::formatTime($info['cTime']);?>
                        </p>
                    </div>
                </li>
            </ul>
            <div class="right-fixed-btns">
                <?php echo CHtml::link('<i class="fa fa-comment"></i><sup>'.$info['comments'].'</sup>','javascript:;',array('action'=>'scroll','action-target'=>'comments-posts-'.$info['id'].'-box'));?>                
            </div>
            <div class="post-content">
                <?php echo $info['content']['content'];?>
            </div>
            <div class="post-replys">
                <?php foreach ($posts as $post){?>
                <?php $this->renderPartial('/posts/_postPost',array('data'=>$post));?>                    
                <?php }?> 
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
    
</div>