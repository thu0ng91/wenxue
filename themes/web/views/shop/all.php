<?php

/**
 * @filename all.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-9-18  16:21:56 
 */
?>
<style>
    .tags-holder {
    padding: 20px 0 20px 20px;
    border-bottom: 1px solid #e4e4e4;
}
.tags-holder .tags-label {
    float: left;
}
.tags-holder .tags-items {
    padding-left: 80px;
}
.tags-holder .tags-items .active {
    color: #FF6666;
}
.tags-holder .tags-items .tags-item {
    min-width: 60px;
    display: inline-block;
    color: #999;
    cursor: pointer;
    padding-right: 10px;
    margin-right: 5px;
}

.tags-holder .tags-items .active a {
    color: #ff6666;
    text-decoration: none;
}


</style>
<div class="container">
    <ol class="breadcrumb">
        <li><?php echo CHtml::link(zmf::config('sitename').'首页',  zmf::config('baseurl'));?></li>
        <li><?php echo CHtml::link('商城',  array('shop/index'));?></li>
        <?php foreach($belongs as $_nav){?>
        <li><?php echo CHtml::link($_nav['title'],array('shop/all','id'=>$_nav['id']));?></li>        
        <?php }?>
    </ol>
    <div class="module">
        <?php foreach ($navbars as $navbar){$seconds=$navbar['items'];?>
        <div class="tags-holder">
            <div class="tags-label"><?php echo $navbar['title'];?>：</div>
            <div class="tags-items">                
                <?php foreach($seconds as $second){?>
                <span class="tags-item"><?php echo CHtml::link($second['title'],array('shop/all','id'=>$second['id']));?></span>
                <?php }?>   
            </div>
        </div>
        <?php }?>
        
        <div class="module-body goods-container">
            <?php foreach ($posts as $post){?>
            <?php $this->renderPartial('_item',array('data'=>$post));?>
            <?php }?>
        </div>
    </div>
</div>