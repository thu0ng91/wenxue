<?php

/**
 * @filename index.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2017-2-21  14:15:08 
 */
?>
<style>
    .module .list-group-noborder{
        margin-bottom: 0;
        padding-top: 0;
        padding-right: 0
    }
    .list-group-noborder .list-group-item:first-child{
        border-radius: 0;
        border-top: none
    }
    .list-group-noborder .list-group-item{
        border: 0;
        border-top: 1px solid #ddd
    }
</style>
<div class="container">            
    <div class="module main-part">
        <div class="module-header">列表</div>
        <div class="module-body">
            <?php foreach($posts as $post){?>
            <div class="media ui-border-b">
                <div class="media-body">
                    <p><?php echo CHtml::link($post['title'],array('wenku/post','id'=>$post['id']),array('target'=>'_blank','title'=>$post['title']));?></p>
                    <p class="color-grey">作者：<?php echo $post['authorName'] ? CHtml::link($post['authorName'],array('wenku/author','id'=>$post['author']),array('target'=>'_blank','title'=>$post['authorName'])) : '佚名';?></p>
                </div>
            </div>
            <?php }?>
            <?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
        </div>
    </div>
    
    <div class="aside-part module">
        <div class="module-header">热门作者</div>
        <div class="module-body list-group list-group-noborder">
            <?php foreach($topAuthors as $topAuthor){?>
            <?php echo CHtml::link($topAuthor['title'],array('/wenku/author','id'=>$topAuthor['id']),array('target'=>'_blank','class'=>'list-group-item'));?>
            <?php }?>
        </div>
        <div class="module-header">热门诗词</div>
        <div class="module-body list-group list-group-noborder">
            <?php foreach($topPosts as $topPost){?>
            <?php echo CHtml::link($topPost['title'],array('/wenku/post','id'=>$topPost['id']),array('target'=>'_blank','class'=>'list-group-item'));?>
            <?php }?>
        </div>
    </div>
</div>