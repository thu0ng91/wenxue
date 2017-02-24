<div class="module">
    <div class="module-header">热门作者</div>
    <div class="module-body">
        <?php if(!empty($authors)){?>
        <?php foreach ($authors as $k=>$author){?>
        <?php if($k==0){?>
        <div class="media top-item">
            <div class="media-left">
                <a href="<?php echo Yii::app()->createUrl('author/view',array('id'=>$author['id']));?>">
                    <img class="media-object lazy" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $author['avatar'];?>" alt="<?php echo $author['title'];?>">
                </a>
            </div>
            <div class="media-body">
                <p class="ui-nowrap title"><?php echo CHtml::link($author['authorName'],array('author/view','id'=>$author['id']));?></p>
                <p class="color-grey"><?php echo zmf::subStr($author['content'],40);?></p>
            </div>
        </div>
        <?php continue;}?>
        <p class="ui-nowrap item"><span class="dot"><?php echo ($k+1);?></span><?php echo CHtml::link($author['authorName'],array('author/view','id'=>$author['id']));?></p>
        <?php }?>
        <?php }?>  
    </div>
</div>