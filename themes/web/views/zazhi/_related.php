<?php

/**
 * @filename _related.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-19  14:32:52 
 */
?>
<?php if(!empty($others)){?>
<div class="module related-posts">
    <div class="row">
        <?php foreach($others as $k=>$_post){?>
        <div class="col-xs-3 col-sm-3">
            <div class="thumbnail">
                <a href="<?php echo Yii::app()->createUrl('zazhi/view',array('zid'=>$_post['id']));?>" title="<?php echo $_post['title'];?>">
                    <img data-original="<?php echo $_post['faceimg'];?>" src="<?php echo zmf::lazyImg();?>" class="img-responsive lazy" alt="<?php echo $_post['title'];?>的封面图"/>
                </a>
                <div class="caption">
                    <h4><?php echo CHtml::link($_post['title'],array('zazhi/view','zid'=>$_post['id']));?></h4>
                </div>
            </div>
        </div>
        <?php if(($k+1)%4==0){?><div class="clearfix"></div><?php }?>
        <?php }?>
    </div>
</div>
<?php }