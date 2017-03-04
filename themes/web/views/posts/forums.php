<div class="container">
    <div class="main-part">
        <div class="module">
            <div class="module-body">
                <?php foreach ($forums as $forum){$_favorited=in_array($forum['id'],$favorites);?>
                <div class="media ui-border-b">
                    <div class="media-left">
                        <a href="<?php echo Yii::app()->createUrl('posts/index',array('forum'=>$forum['id']));?>"><img src="<?php echo zmf::lazyImg();?>" alt="<?php echo $forum['title'];?>" class="lazy a64 img-circle" data-original="<?php echo $forum['faceImg'];?>"/></a>
                    </div>
                    <div class="media-body">
                        <p>
                            <?php echo CHtml::link($forum['title'],array('posts/index','forum'=>$forum['id']),array('class'=>'title'));?>
                            <span class="pull-right"><?php echo GroupPowers::link('favoriteForum',$this->userInfo,($_favorited ? '<i class="fa fa-check"></i> 已关注' : '<i class="fa fa-plus"></i> 关注'),'javascript:;',array('class'=>'btn btn-'.($_favorited ? 'default':'success').' btn-xs','action'=>'favorite','action-data'=>$forum['id'],'action-type'=>'forum'));?></span>
                        </p>
                        <p>关注 <?php echo $forum['favors'];?>  帖子 <?php echo $forum['posts'];?></p>
                        <p class="color-grey"><?php echo $forum['desc'];?></p>  
                        <?php if(!empty($forum['newPosts'])){foreach($forum['newPosts'] as $_post){?>
                        <p class="ui-nowrap"><?php echo CHtml::link($_post['title'],array('posts/view','id'=>$_post['id']),array('target'=>'_blank'));?></p>
                        <?php }}?>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="aside-part">        
        <?php if(!empty($posts)){?>
        <div class="module">
            <div class="module-header">近期发表</div>
            <div class="module-body">
                <?php foreach ($posts as $post){?>
                <p class="ui-nowrap"><?php echo CHtml::link($post['title'],array('posts/view','id'=>$post['id']),array('target'=>'_blank'));?></p>
                <?php }?>
            </div>
        </div>    
        <?php }?>
        <?php if(!empty($topPosts)){?>
        <div class="module">
            <div class="module-header">近期热帖</div>
            <div class="module-body">
                <?php foreach ($topPosts as $post){?>
                <p class="ui-nowrap"><?php echo CHtml::link($post['title'],array('posts/view','id'=>$post['id']),array('target'=>'_blank'));?></p>
                <?php }?>
            </div>
        </div>    
        <?php }?>
    </div>
</div>