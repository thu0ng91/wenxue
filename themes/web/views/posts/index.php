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
<div class="container forum-page">
    <div class="main-part">
        <div class="module">
            <div class="module-header">
                <?php echo $label;?>                
                <?php 
                if($type=='author'){
                    if($this->userInfo['authorId']>0 || $this->userInfo['isAdmin']){
                        echo CHtml::link('<i class="fa fa-plus"></i> 发布文章',array('posts/create','type'=>$type),array('class'=>'pull-right'));
                    }
                }else{
                    echo CHtml::link('<i class="fa fa-plus"></i> 发布文章',array('posts/create','type'=>$type),array('class'=>'pull-right'));
                }
                ?>
            </div>
            <div class="module-body">
                <?php foreach ($posts as $k=>$_post){?>
                <?php $this->renderPartial('/posts/_item',array('data'=>$_post,'posts'=>$posts));?>
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
                        <img src="<?php echo zmf::lazyImg();?>" data-original="<?php echo zmf::config('baseurl').'common/images/weibo.png';?>" class="img-responsive lazy"/>
                        <p class="text-center">官方微博</p>
                    </div>
                    <div class="col-xs-6">
                        <img src="<?php echo zmf::lazyImg();?>" data-original="<?php echo zmf::config('baseurl').'common/images/weixin.jpg';?>" class="img-responsive lazy"/>
                        <p class="text-center">官方微信</p>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>