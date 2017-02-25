<?php

/**
 * @filename carousel.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-19  13:50:35 
 */
$uuid=  uniqid();
$carouselPerSize=12;
?>
<div class="module">
    <h2 class="module-header"><?php echo $moduleInfo['title'];$_posts1=$moduleInfo['posts'];?></h2>
    <div class="module-body">
        <?php foreach ($_posts1 as $_post){?>
            <div class="top-book-item">
                <a href="<?php echo Yii::app()->createUrl('book/view',array('id'=>$_post['id']));?>" target="_blank">
                    <img src="<?php echo zmf::lazyImg();?>" class="img-responsive lazy" data-original="<?php echo $_post['faceImg'];?>" alt="<?php echo $_post['title'];?>"/>
                </a>
                <p class="ui-nowrap item-title"><?php echo CHtml::link($_post['title'],array('book/view','id'=>$_post['id']),array('target'=>'_blank'));?></p>
                <p class="ui-nowrap"><?php echo CHtml::link($_post['authorName'],array('author/view','id'=>$_post['aid']),array('target'=>'_blank'));?></p>
            </div>
        <?php }?>
        <?php if($this->userInfo['isAdmin']){?><div class="column-fixed-btn"><?php echo CHtml::link('<i class="fa fa-edit"></i>',array('admin/showcaseLink/index','sid'=>$moduleInfo['id']),array('target'=>'_blank'));?></div><?php }?>
    </div>
</div>