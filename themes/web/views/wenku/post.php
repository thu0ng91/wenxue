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
        <h1><?php echo $info['title'];?></h1>
        <p class="author"><?php echo $authorInfo ? '- '.CHtml::link($authorInfo['title'],array('wenku/author','id'=>$authorInfo['id']),array('target'=>'_blank')) : '佚名';?></p>
        <div class="content">
            <?php echo nl2br($info['content']);?>
        </div>
    </div>
</div>
<div class="container">
    <div class="main-part module">
        <?php foreach ($aboutInfos as $abInfo){?>
        <div class="module-header"><?php echo WenkuPostInfo::exClassify($abInfo['classify']);?></div>
        <div class="module-body">
            <div class="displayNone" id="content-info-<?php echo $abInfo['id'];?>"><?php echo $abInfo['content'];?><p class="text-right"><?php echo CHtml::link('收起','javascript:;',array('data-id'=>'info-'.$abInfo['id'],'class'=>'toggle'));?></p></div>
            <div id="substr-info-<?php echo $abInfo['id'];?>"><?php echo zmf::subStr($abInfo['content'], 200,'...'.CHtml::link('展开','javascript:;',array('data-id'=>'info-'.$abInfo['id'],'class'=>'toggle')));?></div>
        </div>
        <?php }?>
        <div class="module-header">原创改编</div>
        <div class="module-body">
            
        </div>
    </div>
    <div class="aside-part module">
        <div class="module-header">关于作者</div>
        <div class="module-body media">
            <div class="media-left">
                <img src="<?php echo zmf::lazyImg();?>" class="lazy w78"/>
            </div>
            <div class="media-body">
                <p class="title"><?php echo CHtml::link($authorInfo['title'],array('wenku/author','id'=>$authorInfo['id']),array('target'=>'_blank'));?></p>
            </div>
        </div>
    </div>
</div>