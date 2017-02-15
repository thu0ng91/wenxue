<div class="container">
    <div class="main-part">
        <div class="module">
            <div class="module-body">
                <?php foreach ($forums as $forum){$_favorited=in_array($forum['id'],$favorites);?>
                <div class="media">
                    <div class="media-left">
                        <img src="<?php echo zmf::lazyImg();?>" class="lazy a64 img-circle" data-original="<?php echo $forum['faceImg'];?>"/>
                    </div>
                    <div class="media-body">
                        <p class="title"><?php echo CHtml::link($forum['title'],array('posts/index','forum'=>$forum['id']));?></p>
                        <p>关注 <?php echo $forum['favors'];?>  帖子 <?php echo $forum['posts'];?></p>
                        <p class="color-grey"><?php echo $forum['desc'];?></p>                        
                    </div>
                    <div class="media-right">
                        <p><?php echo GroupPowers::link('favoriteForum',$this->userInfo,($_favorited ? '<i class="fa fa-check"></i> 已关注' : '<i class="fa fa-plus"></i> 关注'),'javascript:;',array('class'=>'btn btn-'.($_favorited ? 'default':'success').' btn-xs','action'=>'favorite','action-data'=>$forum['id'],'action-type'=>'forum'));?></p>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="aside-part">
        <?php if(!empty($posts)){?>
            <div class="module-header">近期发表</div>
            <div class="module-body">
                <?php foreach ($posts as $post){?>
                <p class="ui-nowrap"><?php echo CHtml::link($post['title'],array('posts/view','id'=>$post['id']));?></p>
                <?php }?>
            </div>
        <?php }?>
    </div>
</div>