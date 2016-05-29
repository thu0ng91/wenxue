<?php

/**
 * @filename sideModule.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-19  16:03:15 
 */
$_sidePosts=$moduleInfo['posts'];
$_columnClass=$class!='' ? $class : 'column-min';
?>
<div class="module <?php echo $_columnClass;?>">
    <div class="module-header">
        <?php echo $moduleInfo['title'];?>
    </div>
    <div class="module-body">
        <?php if(!empty($_sidePosts)){?>
        <?php if($_columnClass=='column-min'){?>
        <?php foreach ($_sidePosts as $k=>$_post){?>
        <p class="ui-nowrap item"><span class="dot">•</span><?php echo CHtml::link(($_post['colTitle']!='' ? '['.$_post['colTitle'].']' : '').$_post['title'],array('book/view','id'=>$_post['id']));?></p> 
        <?php }?>
        <?php }else{?>
        <div class="row">
        <?php foreach ($_sidePosts as $k=>$_post){?>
            <div class="col-xs-6">
                <div class="media">
                    <div class="media-left">
                        <a href="<?php echo Yii::app()->createUrl('book/view',array('id'=>$_post['id']));?>" target="_blank">
                            <img class="media-object" src="<?php echo $_post['faceImg'];?>" alt="<?php echo $_post['title'];?>" title="<?php echo $_post['title'];?>">
                        </a>
                    </div>
                    <div class="media-body">
                        <p class="no-wrap title"><?php echo CHtml::link($_post['title'],array('book/view','id'=>$_post['id']),array('target'=>'_blank'));?></p>
                        <p class="color-grey"><?php echo zmf::subStr($_post['desc']);?></p>
                    </div>
                </div>
            </div>
        <?php }?>
        </div>
        <?php }?>
        <?php }?>
    </div>
</div>