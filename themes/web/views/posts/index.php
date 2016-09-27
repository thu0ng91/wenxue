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
    <div class="module">
        <div class="module-body">
            <div class="media">
                <div class="media-left">
                    <?php echo CHtml::link(CHtml::image(zmf::lazyImg(), $forumInfo['title'], array('data-original' => $forumInfo['faceImg'], 'class' => 'lazy img-circle a108 media-object')), array('posts/index','forum'=>$forumInfo['id'])); ?>
                </div>
                <div class="media-body">
                    <h1><?php echo $forumInfo['title'];?></h1>
                    <p><?php echo $forumInfo['desc'];?></p>
                </div>
                <div class="media-right">
                    <div class="" style="width: 80px">
                        <p><?php echo GroupPowers::link('favoriteForum',$this->userInfo,($_favorited ? '<i class="fa fa-check"></i> 已关注' : '<i class="fa fa-plus"></i> 关注'),'javascript:;',array('class'=>'btn btn-'.($_favorited ? 'default':'success').' btn-xs btn-block','action'=>'favorite','action-data'=>$forumInfo['id'],'action-type'=>'forum'));?></p>
                        <p><?php echo GroupPowers::link('addPost',$this->userInfo,'<i class="fa fa-plus"></i> 发表新帖',array('posts/create','forum'=>$forumInfo['id']),array('class'=>'btn btn-default btn-xs btn-block'));?></p>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>
    <div class="main-part">        
        <div class="module">
            <div class="module-body">
                <?php foreach ($posts as $k=>$_post){?>
                <?php $this->renderPartial('/posts/_item',array('data'=>$_post,'posts'=>$posts,'k'=>$k));?>
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
            <?php if($this->userInfo['isAdmin']){?><div class="column-fixed-btn"><?php echo CHtml::link('<i class="fa fa-edit"></i>',array('admin/showcaseLink/index','sid'=>$showcases['author']['id']),array('target'=>'_blank'));?></div><?php }?>
        </div>
        <?php }?>
        <?php $sideTops=$showcases[$type.'Top'];if(!empty($sideTops)){$_sideTops=$sideTops['post'];?>
        <div class="module">
            <div class="module-header"><?php echo $sideTops['title'];?></div>
            <div class="module-body">
                <?php  foreach ($_sideTops as $_side){?>
                <p><?php echo CHtml::link($_side['title'],$_side['url'],array('target'=>'_blank'));?></p>
                <?php }?>
                <?php if($this->userInfo['isAdmin']){?><div class="column-fixed-btn"><?php echo CHtml::link('<i class="fa fa-edit"></i>',array('admin/showcaseLink/index','sid'=>$sideTops['id']),array('target'=>'_blank'));?></div><?php }?>
            </div>
        </div>
        <?php }?>
        <div class="module">
            <div class="module-header">打赏榜</div>
            <div class="module-body">
                      
            </div>
        </div>
        <div class="module">
            <div class="module-header">活跃用户</div>
            <div class="module-body">
                      
            </div>
        </div>
        <?php if(!empty($forums)){?>
        <div class="list-group">
            <?php foreach ($forums as $forum){?>
            <?php echo CHtml::link($forum['title'],array('posts/index','forum'=>$forum['id']),array('class'=>'list-group-item'));?>
            <?php }?>
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