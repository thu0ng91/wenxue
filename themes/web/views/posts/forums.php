<div class="container">
    <div class="main-part">
        <div class="module">
            <div class="module-body">
                <?php foreach ($forums as $forum){?>
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
                        <p><?php echo CHtml::link('<i class="fa fa-plus"></i> 关注','javascript:;',array('class'=>'btn btn-default btn-xs'));?></p>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
</div>