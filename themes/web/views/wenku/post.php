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
        <p class="author"><?php echo $info['author'] ? CHtml::link($info['author'],array('wenku/author','id'=>$info['author'])) : '佚名';?></p>
        <div class="content">
            <?php echo $info['content'];?>
        </div>
    </div>
</div>
<div class="container">
    <div class="main-part module">
        <?php $aboutInfos=$info->aboutInfos; foreach ($aboutInfos as $abInfo){?>
        <div class="module-header"><?php echo WenkuPostInfo::exClassify($abInfo['classify']);?></div>
        <div class="module-body">
            <?php echo $abInfo['content'];?>
        </div>
        <?php }?>
    </div>
    <div class="aside-part module">
        <div class="module-header">关于作者</div>
    </div>
</div>