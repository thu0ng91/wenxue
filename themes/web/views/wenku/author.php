<?php

/**
 * @filename author.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2017-2-21  14:15:13 
 */
?>
<?php if($info['bgImg']){?>
<style>.jumbotron-post{background: url(<?php echo $info['bgImg'];?>) no-repeat center;background-size: cover}</style>
<?php }?>
<div class="container-fluid jumbotron-post">
    <div class="container text-center">
        <img src="<?php echo zmf::lazyImg();?>" class="lazy img-circle a108" data-original='<?php echo zmf::getThumbnailUrl($info['faceImg'],'a120');?>'/>
        <h1><?php echo $info['title'];?></h1>        
    </div>
</div>
<div class="container">            
    <div class="module main-part">
        <?php foreach($aboutInfos as $abInfo){?>
        <div class="module-header"><?php echo $abInfo['title'] ? $abInfo['title'] : ($info['title']. WenkuAuthorInfo::exClassify($abInfo['classify']));?></div>
        <div class="module-body">
            <div class="displayNone" id="content-info-<?php echo $abInfo['id'];?>"><?php echo $abInfo['content'];?><p class="text-right"><?php echo CHtml::link('收起','javascript:;',array('data-id'=>'info-'.$abInfo['id'],'class'=>'toggle'));?></p></div>
            <div id="substr-info-<?php echo $abInfo['id'];?>"><?php echo zmf::subStr($abInfo['content'], 200,'...'.CHtml::link('展开','javascript:;',array('data-id'=>'info-'.$abInfo['id'],'class'=>'toggle')));?></div>
        </div>
        <?php }?>
    </div>
    
    <div class="aside-part module">
        <div class="module-header"><?php echo $info['title'];?>热门作品</div>
        <div class="module-body list-group list-group-noborder">
            <?php foreach($posts as $topPost){?>
            <?php echo CHtml::link($topPost['title'],array('/wenku/post','id'=>$topPost['id']),array('target'=>'_blank','class'=>'list-group-item'));?>
            <?php }?>
        </div>
        
    </div>
</div>