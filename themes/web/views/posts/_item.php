<div class="media post-item <?php echo $data['top'] && !$posts[$k+1]['top'] ? 'last-toped' : '';?>">
    <?php if($data['faceimg']){?>
    <div class="media-left">
        <a href="<?php echo Yii::app()->createUrl('posts/view',array('id'=>$data['id']));?>">
            <img class="media-object lazy w70" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $data['faceimg'];?>" alt="<?php echo $data['title'];?>">
        </a>
    </div>
    <?php }?>
    <div class="media-body">
        <p class="no-wrap <?php echo Posts::exTopClass($data['styleStatus']);?>"><?php echo CHtml::link($data['title'],array('posts/view','id'=>$data['id']));?></p>
        <p class="color-grey tips">
            <?php if($data['top']){?>
            <span style="color:red" title="置顶"><i class="fa fa-bookmark"></i></span>
            <?php }?>
            <?php if($data['styleStatus']){?>
            <span style="color:red" title="加精"><i class="fa fa-flag"></i></span>
            <?php }?>
            <?php if($data['classify']==Posts::CLASSIFY_AUTHOR && $data['aid']){?>
            <span><?php echo CHtml::link($data['username'],array('author/view','id'=>$data['aid']));?></span>  
            <?php }else{?>
            <span><?php echo CHtml::link($data['username'],array('user/index','id'=>$data['uid']));?></span>  
            <?php }?>
            <span><?php echo zmf::formatTime($data['cTime']);?></span>                            
            <span><?php echo $data['comments'];?>评论</span>                            
            <span><?php echo $data['favorite'];?>赞</span>                            
        </p>
    </div>
</div>