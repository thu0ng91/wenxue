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
                    <?php echo CHtml::link(CHtml::image(zmf::lazyImg(), $forumInfo['title'], array('data-original' => $forumInfo['faceImg'], 'class' => 'lazy img-circle a64 media-object')), array('posts/index','forum'=>$forumInfo['id'])); ?>
                </div>
                <div class="media-body">
                    <h1><?php echo $forumInfo['title'];?></h1>
                    <p class="color-grey"><?php echo $forumInfo['desc'];?></p>
                </div>
                <div class="media-right">
                    <div class="" style="width: 80px">
                        <p><?php echo !$favorited ? GroupPowers::link('favoriteForum',$this->userInfo,('<i class="fa fa-plus"></i> 关注'),'javascript:;',array('class'=>'btn btn-success btn-xs btn-block','action'=>'favorite','action-data'=>$forumInfo['id'],'action-type'=>'forum')) : '';?></p>
                        <p><?php echo PostForums::addPostOrNot($forumInfo, $this->userInfo) ? GroupPowers::link('addPost',$this->userInfo,'<i class="fa fa-plus"></i> 发表新帖',array('posts/create','forum'=>$forumInfo['id']),array('class'=>'btn btn-default btn-xs btn-block')) : '';?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-part">  
        <ul class="nav nav-tabs nav-posts" role="tablist">
            <li role="presentation" <?php echo $filter=='zmf' ? 'class="active"': '';?>><?php echo zmf::urls('最新','posts/index',array('key'=>'filter','value'=>''));?></li>
            <li role="presentation" <?php echo $filter=='digest' ? 'class="active"': '';?>><?php echo zmf::urls('精华','posts/index',array('key'=>'filter','value'=>'digest'));?></li>
            <li role="presentation" class="dropdown pull-right">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    排序 <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li role="presentation" <?php echo $order=='zmf' ? 'class="active"': '';?>><?php echo zmf::urls('默认','posts/index',array('key'=>'order','value'=>''));?></li>
                    <li role="presentation" <?php echo $order=='hits' ? 'class="active"': '';?>><?php echo zmf::urls('点击','posts/index',array('key'=>'order','value'=>'hits'));?></li>
                    <li role="presentation" <?php echo $order=='props' ? 'class="active"': '';?>><?php echo zmf::urls('赞赏','posts/index',array('key'=>'order','value'=>'props'));?></li>
                </ul>
            </li>
        </ul>
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
        <div class="module" style="display: none">
            <div class="module-header">打赏榜</div>
            <div class="module-body">
                      
            </div>
        </div>
        <?php if(!empty($topUsers)){?>
        <div class="module">
            <div class="module-header">活跃用户</div>
            <div class="module-body activeUsers">
                <?php foreach ($topUsers as $topUser){?>
                 <?php echo CHtml::link(CHtml::image(zmf::lazyImg(), $topUser['truename'], array('data-original' => $topUser['avatar'], 'class' => 'lazy a36 media-object')), array('user/index','id'=>$topUser['id']),array('title'=>$topUser['truename'])); ?> 
                <?php }?>
            </div>
        </div>
        <?php }?>
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
                        <img src="<?php echo zmf::lazyImg();?>" data-original="https://img2.chuxincw.com/siteinfo/2017/02/18/B46BF6E6-CC2D-A93E-1217-547B852B85E1.png/a120" class="a108 lazy" alt="初心创文官方微博"/>
                        <p class="text-center">官方微博</p>
                    </div>
                    <div class="col-xs-6">
                        <img src="<?php echo zmf::lazyImg();?>" data-original="https://img2.chuxincw.com/siteinfo/2017/02/18/78FC4DC2-0627-66F0-E5A3-8AEDA399BAC0.jpg/a120" class="a108 lazy" alt="初心创文官方微信"/>
                        <p class="text-center">官方微信</p>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>