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
    .forum-page .main-part .module-body{
        padding-bottom: 0;
    }
    .forum-page .aside-part .module-body{
        padding-bottom: 20px;
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
    .forum-page .posts-side-show img{
        width: 300px;
        height: 225px;
    }
    .yiiPager{
        clear: both;
        display: block
    }
</style>
<div class="container forum-page">
    <div class="main-part">
        <div class="module">
            <div class="module-header">
                <?php echo $label;?>                
                <?php 
                if($type=='author'){
                    if($this->userInfo['authorId']>0){
                        echo CHtml::link('<i class="fa fa-plus"></i> 发布文章',array('posts/create','type'=>$type),array('class'=>'pull-right'));
                    }
                }else{
                    echo CHtml::link('<i class="fa fa-plus"></i> 发布文章',array('posts/create','type'=>$type),array('class'=>'pull-right'));
                }
                ?>
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
                            <?php if($_post['classify']==Posts::CLASSIFY_AUTHOR){?>
                            <span><?php echo CHtml::link($_post['username'],array('author/view','id'=>$_post['aid']));?></span>  
                            <?php }else{?>
                            <span><?php echo CHtml::link($_post['username'],array('user/index','id'=>$_post['uid']));?></span>  
                            <?php }?>
                            <span><?php echo zmf::formatTime($_post['cTime']);?></span>                            
                            <span><?php echo $_post['comments'];?>评论</span>                            
                            <span><?php echo $_post['favors'];?>赞</span>                            
                        </p>
                    </div>
                </div>
                <?php }?>
                <?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
            </div>
        </div>
    </div>
    <div class="aside-part">
        <?php $sideAds=$showcases['author']['post'];if(!empty($sideAds)){$_info=$sideAds[0];?>
        <div class="module posts-side-show">
            <a href="<?php echo $_info['url']!='' ? $_info['url'] : 'javascript:;';?>" target="_blank">
                <img src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $_info['faceimg'];?>" class="img-responsive lazy"/>
            </a>
        </div>
        <?php }?>
        <?php $sideTops=$showcases[$type.'Top'];if(!empty($sideTops)){$_sideTops=$sideTops['post'];?>
        <div class="module">
            <div class="module-header"><?php echo $sideTops['title'];?></div>
            <div class="module-body">
                <?php  foreach ($_sideTops as $_side){?>
                <p><?php echo CHtml::link($_side['title'],$_side['url'],array('target'=>'_blank'));?></p>
                <?php }?>
            </div>
        </div>
        <?php }?>
        <div class="module">
            <div class="module-header">关注我们</div>
            <div class="module-body">
                <div class="row">
                    <div class="col-xs-6">
                        <img src="<?php echo zmf::lazyImg();?>" data-original="<?php echo zmf::config('baseurl').'common/images/qrcode.png';?>" class="img-responsive lazy"/>
                        <p class="text-center">官方微博</p>
                    </div>
                    <div class="col-xs-6">
                        <img src="<?php echo zmf::lazyImg();?>" data-original="<?php echo zmf::config('baseurl').'common/images/qrcode.png';?>" class="img-responsive lazy"/>
                        <p class="text-center">官方微信</p>
                    </div>
                </div>                
            </div>
        </div>
        <?php $this->renderPartial('/common/copyright');?>
    </div>
</div>