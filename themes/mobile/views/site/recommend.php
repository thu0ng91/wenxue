<?php

/**
 * @filename recommend.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-10-28  15:31:40 
 */
?>
<div class="recommend">
    <div class="module">
        <div class="module-header">推荐作者</div>
        <div class="module-body">
            <ul class="ui-list ui-list-function ui-border-tb">
            <?php foreach ($authors as $author){?>
            <li class="ui-border-t">
                <div class="ui-avatar">
                    <span style="background-image:url(<?php echo $author['avatar'];?>)"></span>
                </div>
                <div class="ui-list-info">                    
                    <p class="title ui-nowrap"><?php echo CHtml::link($author['authorName'],array('author/view','id'=>$author['id']),array('target'=>'_blank'));?></p>   
                    <p class="color-grey ui-nowrap"><?php echo $author['content'];?></p>
                </div>
                <?php if($favoriteAuthor){?>
                <?php echo CHtml::link('<i class="fa fa-plus"></i> 关注','javascript:;',array('class'=>'ui-btn','action'=>'favorite','action-data'=>$author['id'],'action-type'=>'author'));?>
                <?php }?>
            </li>
            <?php }?>
            </ul>
        </div>
    </div>
    
    <div class="module">
        <div class="module-header">活跃用户</div>
        <div class="module-body">
            <ul class="ui-list ui-list-function ui-border-tb">
            <?php foreach ($users as $user){?>
            <li class="ui-border-t">
                <div class="ui-avatar">
                    <span style="background-image:url(<?php echo $user['avatar'];?>)"></span>
                </div>
                <div class="ui-list-info">                    
                    <p class="title ui-nowrap"><?php echo CHtml::link($user['truename'],array('user/index','id'=>$user['id']),array('target'=>'_blank'));?></p>   
                    <p class="color-grey ui-nowrap"><?php echo $user['content'];?></p>
                </div>
                <?php if($favoriteUser){?>
                <?php echo CHtml::link('<i class="fa fa-plus"></i> 关注','javascript:;',array('class'=>'ui-btn','action'=>'favorite','action-data'=>$user['id'],'action-type'=>'user'));?>
                <?php }?>
            </li>
            <?php }?>
            </ul>
        </div>
    </div>
    
    <div class="ui-btn-wrap">
        <?php echo CHtml::link('下一步',  $url,array('class'=>'ui-btn-lg ui-btn-primary'));?>
    </div>
</div>