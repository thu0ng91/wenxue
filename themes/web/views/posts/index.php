<?php

/**
 * @filename index.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-19  17:02:32 
 */
?>
<style>
    .forum-page .module{
        padding: 0
    }
    .forum-page .post-item{
        border-bottom: 1px dashed #eee
    }
    .forum-page .post-item .title{
        font-weight: 700
    }
    .forum-page .post-item .tips span{
        margin-right: 5px
    }
</style>
<div class="container forum-page">
    <div class="main-part">
        <div class="module">
            <div class="module-header">
                <?php echo $label;?>
                <?php echo CHtml::link('<i class="fa fa-plus"></i> 发布文章',array('posts/create','type'=>$type),array('class'=>'pull-right'));?>
            </div>
            <div class="module-body">
                <?php foreach ($posts as $_post){?>
                <div class="media post-item">
                    <?php if($_post['faceimg']){?>
                    <div class="media-left">
                        <a href="<?php echo Yii::app()->createUrl('posts/view',array('id'=>$_post['id']));?>">
                            <img class="media-object" src="<?php echo $_post['faceimg'];?>" alt="<?php echo $_post['title'];?>">
                        </a>
                    </div>
                    <?php }?>
                    <div class="media-body">
                        <p class="no-wrap title"><?php echo CHtml::link($_post['title'],array('posts/view','id'=>$_post['id']));?></p>
                        <p class="color-grey tips">
                            <span>大飞</span>                            
                            <span><?php echo zmf::formatTime($_post['cTime']);?></span>                            
                            <span><?php echo $_post['comments'];?>评论</span>                            
                            <span><?php echo $_post['favors'];?>赞</span>                            
                        </p>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="aside-part">
        <div class="module">
            
        </div>
        <p>
            
        </p>
        
        <?php $this->renderPartial('/common/copyright');?>
    </div>
</div>