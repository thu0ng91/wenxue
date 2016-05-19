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
        <?php foreach ($_sidePosts as $k=>$_post){?>
        <?php if( ($moduleInfo['display']=='thumbFirst' && $k==0) || ($moduleInfo['display']=='thumbThird' && $k<3) ){?>
        <div class="media top-item">
            <div class="media-left">
                <a href="<?php echo Yii::app()->createUrl('book/view',array('id'=>$_post['id']));?>">
                    <img class="media-object" src="<?php echo $_post['faceImg'];?>" alt="<?php echo $_post['title'];?>">
                </a>
            </div>
            <div class="media-body">
                <p class="no-wrap title"><?php echo CHtml::link(($_post['colTitle']!='' ? '['.$_post['colTitle'].']' : '').$_post['title'],array('book/view','id'=>$_post['id']));?></p>
                <p class="color-grey"><?php echo zmf::subStr($_post['desc'],40);?></p>
            </div>
        </div>
        <?php continue;}?>
        <p class="ui-nowrap item"><span class="dot"><?php echo ($k+1);?></span><?php echo CHtml::link(($_post['colTitle']!='' ? '['.$_post['colTitle'].']' : '').$_post['title'],array('book/view','id'=>$_post['id']));?></p>   
        <p class="ui-nowrap item"><span class="dot"><?php echo ($k+1);?></span><?php echo CHtml::link(($_post['colTitle']!='' ? '['.$_post['colTitle'].']' : '').$_post['title'],array('book/view','id'=>$_post['id']));?></p>   
        <p class="ui-nowrap item"><span class="dot"><?php echo ($k+1);?></span><?php echo CHtml::link(($_post['colTitle']!='' ? '['.$_post['colTitle'].']' : '').$_post['title'],array('book/view','id'=>$_post['id']));?></p>   
        <p class="ui-nowrap item"><span class="dot"><?php echo ($k+1);?></span><?php echo CHtml::link(($_post['colTitle']!='' ? '['.$_post['colTitle'].']' : '').$_post['title'],array('book/view','id'=>$_post['id']));?></p>   
        <p class="ui-nowrap item"><span class="dot"><?php echo ($k+1);?></span><?php echo CHtml::link(($_post['colTitle']!='' ? '['.$_post['colTitle'].']' : '').$_post['title'],array('book/view','id'=>$_post['id']));?></p>   
        <p class="ui-nowrap item"><span class="dot"><?php echo ($k+1);?></span><?php echo CHtml::link(($_post['colTitle']!='' ? '['.$_post['colTitle'].']' : '').$_post['title'],array('book/view','id'=>$_post['id']));?></p>   
        <p class="ui-nowrap item"><span class="dot"><?php echo ($k+1);?></span><?php echo CHtml::link(($_post['colTitle']!='' ? '['.$_post['colTitle'].']' : '').$_post['title'],array('book/view','id'=>$_post['id']));?></p>   
        <p class="ui-nowrap item"><span class="dot"><?php echo ($k+1);?></span><?php echo CHtml::link(($_post['colTitle']!='' ? '['.$_post['colTitle'].']' : '').$_post['title'],array('book/view','id'=>$_post['id']));?></p>   
        <?php }?>
        <?php }?>
    </div>
</div>