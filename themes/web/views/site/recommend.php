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
        <div class="module-body padding-body">
            <?php foreach ($authors as $author){?>
            <div class="thumbnail">
                <img src="<?php echo zmf::lazyImg();?>" class="lazy" data-original="<?php echo $author['avatar'];?>" alt="<?php echo $author['authorName'];?>"/>
                <div class="caption">                    
                    <p class="title ui-nowrap"><?php echo CHtml::link($author['authorName'],array('author/view','id'=>$author['id']),array('target'=>'_blank'));?></p>   
                    <p class="color-grey ui-nowrap-multi author-desc"><?php echo $author['content'];?></p>
                    <?php if($favoriteAuthor){?>
                    <p class="author-btn"><?php echo CHtml::link('<i class="fa fa-plus"></i> 关注','javascript:;',array('class'=>'btn btn-danger btn-xs btn-block','action'=>'favorite','action-data'=>$author['id'],'action-type'=>'author'));?></p>
                    <?php }?>
                </div>
            </div>
            <?php }?>
        </div>
    </div>
    <div class="module">
        <div class="module-header">活跃用户</div>
        <div class="module-body padding-body">
            <?php foreach ($users as $user){?>
            <div class="thumbnail">
                <img src="<?php echo zmf::lazyImg();?>" class="lazy" data-original="<?php echo $user['avatar'];?>" alt="<?php echo $user['truename'];?>"/>
                <div class="caption">                    
                    <p class="title ui-nowrap"><?php echo CHtml::link($user['truename'],array('user/index','id'=>$user['id']),array('target'=>'_blank'));?></p>   
                    <p class="color-grey ui-nowrap-multi author-desc"><?php echo $user['content'];?></p>
                    <?php if($favoriteUser){?>
                    <p class="author-btn"><?php echo CHtml::link('<i class="fa fa-plus"></i> 关注','javascript:;',array('class'=>'btn btn-danger btn-xs btn-block','action'=>'favorite','action-data'=>$user['id'],'action-type'=>'user'));?></p>
                    <?php }?>
                </div>
            </div>
            <?php }?>
        </div>
    </div>
    <?php if($this->uid){?>
    <p><?php echo CHtml::link('下一步',array('user/index','id'=>$this->uid),array('class'=>'btn btn-success btn-lg btn-block'));?></p>
    <?php }else{?>
    <p><?php echo CHtml::link('下一步',  zmf::config('baseurl'),array('class'=>'btn btn-success btn-lg btn-block'));?></p>
    <?php }?>
</div>