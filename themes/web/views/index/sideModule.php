<?php

/**
 * @filename sideModule.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-19  16:03:15 
 */
$_sidePosts=$sideInfo['posts'];
?>
<div class="module">
    <div class="module-header">
        <?php echo $sideInfo['title'];?>
    </div>
    <div class="module-body">
        <?php if(!empty($_sidePosts)){?>
        <?php foreach ($_sidePosts as $k=>$_post){?>
        <?php if( ($sideInfo['display']=='thumbFirst' && $k==0) || ($sideInfo['display']=='thumbThird' && $k<3) ){?>
        <div class="media top-item">
            <div class="media-left">
                <a href="<?php echo Yii::app()->createUrl('book/view',array('id'=>$_post['id']));?>">
                    <img class="media-object lazy" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $_post['faceImg'];?>" alt="<?php echo $_post['title'];?>">
                </a>
            </div>
            <div class="media-body">
                <p class="ui-nowrap title"><?php echo ($_post['colTitle']!='' ? CHtml::link('['.$_post['colTitle'].']',array('book/index','colid'=>$_post['colid'])) : '').CHtml::link($_post['title'],array('book/view','id'=>$_post['id']));?></p>
                <p class="color-grey"><?php echo zmf::subStr($_post['desc'],40);?></p>
            </div>
        </div>
        <?php continue;}?>
        <p class="ui-nowrap item"><span class="dot"><?php echo ($k+1);?></span><?php echo ($_post['colTitle']!='' ? CHtml::link('['.$_post['colTitle'].']',array('book/index','colid'=>$_post['colid'])) : '').CHtml::link($_post['title'],array('book/view','id'=>$_post['id']));?></p>
        <?php }?>
        <?php }?>  
        <?php if($this->userInfo['isAdmin']){?><div class="column-fixed-btn"><?php echo CHtml::link('<i class="fa fa-edit"></i>',array('admin/showcaseLink/index','sid'=>$sideInfo['id']),array('target'=>'_blank'));?></div><?php }?>
    </div>
</div>