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
<div class="container-fluid jumbotron-post">
    <div class="container">
        <img src="<?php echo zmf::lazyImg();?>" class="lazy img-circle a108" data-original='https://img2.chuxincw.com/siteinfo/2017/02/18/32DD3F5E-18B2-DA78-549C-9E7F89731A1B.png/a120'/>
        <h1><?php echo $info['title'];?></h1>        
    </div>
</div>
<div class="container">            
    <div class="module main-part">
        <?php foreach($aboutInfos as $aboutInfo){?>
        <div class="module-header"><?php echo $info['title']. WenkuAuthorInfo::exClassify($aboutInfo['classify']);?></div>
        <div class="module-body">
            <?php echo $aboutInfo['content'];?>
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