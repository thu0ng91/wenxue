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
<div class="forum-page">    
    <div class="module">
        <div class="module-body">
            <ul class="ui-list">
                <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('posts/index',array('forum'=>$forumInfo['id']));?>">
                    <div class="ui-avatar a50">
                        <img class="lazy a50" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $forumInfo['faceImg'];?>" alt="<?php echo $forumInfo['title'];?>">
                    </div>
                    <div class="ui-list-info">
                        <p class="ui-nowrap title"><?php echo $forumInfo['title'];?></p>
                        <p class="ui-nowrap-multi color-grey"><?php echo $forumInfo['desc'];?></p>
                        <div class="ui-btn-wrap">
                            <?php echo GroupPowers::link('addPost',$this->userInfo,'<i class="fa fa-plus"></i> 发表新帖',array('posts/create','forum'=>$forumInfo['id']),array('class'=>'ui-btn ui-btn-primary'));?>
                            <?php echo !$favorited ? GroupPowers::link('favoriteForum',$this->userInfo,('<i class="fa fa-plus"></i> 关注'),'javascript:;',array('class'=>'ui-btn ui-btn-danger','action'=>'favorite','action-data'=>$forumInfo['id'],'action-type'=>'forum')) : '';?>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="module">
        <div class="module-body">
            <ul class="ui-list">
            <?php foreach ($posts as $k=>$_post){?>
                <li class="ui-border-t <?php echo $_post['top'] && !$posts[$k+1]['top'] ? 'last-toped' : '';?>" data-href="<?php echo Yii::app()->createUrl('posts/view',array('id'=>$_post['id']));?>">
                    <?php if($_post['faceImg']){?>
                    <div class="ui-list-img">
                        <span style="background-image:url(<?php echo $_post['faceImg'];?>)"></span>
                    </div>
                    <?php }?>
                    <div class="ui-list-info">
                        <h4 class="ui-nowrap-multi <?php echo Posts::exTopClass($_post['styleStatus']);?>"><?php echo $_post['title'];?></h4>
                        <p class="color-grey tips ui-nowrap">
                            <?php if($_post['top']){?>
                            <span style="color:red" title="置顶"><i class="fa fa-bookmark"></i></span>
                            <?php }?>
                            <?php if($_post['styleStatus']){?>
                            <span style="color:red" title="加精"><i class="fa fa-flag"></i></span>
                            <?php }?>
                            <span><?php echo $_post['username'];?></span>
                            <span><?php echo zmf::formatTime($_post['cTime']);?></span>                            
                            <span class="pull-right">
                                <i class="fa fa-eye"></i> <?php echo $_post['hits'];?>
                                <i class="fa fa-comment"></i> <?php echo $_post['posts'];?>
                            </span>
                        </p>
                    </div>
                </li>
            <?php }?>
            </ul>                
        </div>
    </div>
</div>
