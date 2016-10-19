<div class="media post-item ui-border-b <?php echo $data['top'] && !$posts[$k+1]['top'] ? 'last-toped' : '';?>">
    <?php if($data['faceImg']){?>
    <div class="media-left">
        <a href="<?php echo Yii::app()->createUrl('posts/view',array('id'=>$data['id']));?>">
            <img class="media-object lazy w70" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $data['faceImg'];?>" alt="<?php echo $data['title'];?>">
        </a>
    </div>
    <?php }?>
    <div class="media-body">
        <p class="no-wrap <?php echo Posts::exTopClass($data['styleStatus']);?>"><?php echo CHtml::link($data['title'],array('posts/view','id'=>$data['id']));?></p>
        <p class="color-grey tips">
            <?php if($data['top']){?>
            <span style="color:red" title="置顶"><i class="fa fa-bookmark"></i></span>
            <?php }?>
            <?php if($data['digest']){?>
            <span style="color:red" title="加精"><i class="fa fa-flag"></i></span>
            <?php }?>            
            <span><?php echo CHtml::link($data['username'],array('user/index','id'=>$data['uid']));?></span>
            <span><?php echo zmf::formatTime($data['cTime']);?></span>   
            <span class="pull-right">
                <span><i class="fa fa-eye"></i> <?php echo $data['hits'];?></span>
                <span><i class="fa fa-comments"></i> <?php echo $data['posts'];?></span>
            </span>
        </p>
    </div>
</div>