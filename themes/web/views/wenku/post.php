<?php

/**
 * @filename post.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2017-2-21  14:15:31 
 */
?>
<div class="container-fluid jumbotron-post">
    <div class="container">
        <h1><?php echo $info['title'];?><?php if($info['second_title']){?><small>——<?php echo $info['second_title'];?></small><?php }?></h1>
        <p class="author"><?php echo $authorInfo ? '——'.($authorInfo['dynastyName'] ? '（'.CHtml::link($authorInfo['dynastyName'],array('wenku/author','id'=>$authorInfo['id']),array('target'=>'_blank')).'）' : '').CHtml::link($authorInfo['title'],array('wenku/author','id'=>$authorInfo['id']),array('target'=>'_blank')) : '佚名';?></p>
        <div class="content">
            <?php echo nl2br($info['content']);?>
        </div>
    </div>
</div>
<div class="container">
    <div class="main-part module">
        <?php foreach ($aboutInfos as $abInfo){?>
        <h2 class="module-header"><?php echo $abInfo['title'] ? $abInfo['title'] : WenkuPostInfo::exClassify($abInfo['classify']);?></h2>
        <div class="module-body">
            <div class="displayNone" id="content-info-<?php echo $abInfo['id'];?>"><?php echo $abInfo['content'];?><p class="text-right"><?php echo CHtml::link('收起','javascript:;',array('data-id'=>'info-'.$abInfo['id'],'class'=>'toggle'));?></p></div>
            <div id="substr-info-<?php echo $abInfo['id'];?>"><?php echo zmf::subStr($abInfo['content'], 200,'...'.CHtml::link('展开','javascript:;',array('data-id'=>'info-'.$abInfo['id'],'class'=>'toggle')));?></div>
        </div>
        <?php }?>
<!--        <h2 class="module-header">原创改编</h2>
        <div class="module-body">
            
        </div>-->
    </div>
    <div class="aside-part module">
        <h2 class="module-header">关于【<?php echo $authorInfo['title'];?>】</h2>
        <div class="module-body media">
            <div class="media-left">
                <img src="<?php echo zmf::lazyImg();?>" class="lazy w78" data-original="<?php echo zmf::getThumbnailUrl($authorInfo['faceImg'],'w120');?>"/>
            </div>
            <div class="media-body">
                <p class="title"><?php echo CHtml::link($authorInfo['title'],array('wenku/author','id'=>$authorInfo['id']),array('target'=>'_blank'));?></p>
                <p><b>朝代：</b><?php echo CHtml::link($authorInfo['dynastyName'],array('wenku/author','id'=>$authorInfo['id']),array('target'=>'_blank'));?></p>
                <p><b>介绍：</b><?php echo zmf::subStr($authorInfo['content'],80,'...'.CHtml::link('查看详情',array('wenku/author','id'=>$authorInfo['id']),array('target'=>'_blank')));?></p>
            </div>
        </div>
    </div>
</div>