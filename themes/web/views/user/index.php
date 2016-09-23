<?php

/**
 * @filename index.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-13  16:27:16 
 */
?>
<div class="module">
    <div class="module-header"><?php echo $this->myself ? '' : '他的';?>动态</div>
    <div class="module-body">
        <?php if(!empty($posts)){?>
        <?php foreach ($posts as $post){?>
        <?php if($post['classify']=='favoriteAuthor'){?>
        <div class="media user-log-item">
            <div class="media-left">
                <a href="<?php echo Yii::app()->createUrl('user/index',array('id'=>$post['uid']));?>" title="<?php echo $post['truename'];?>">
                    <img class="media-object lazy a48 img-circle" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $post['avatar'];?>" alt="<?php echo $post['truename'];?>">
                </a>
            </div>
            <div class="media-body">
                <p><?php echo CHtml::link($post['truename'],array('user/index','id'=>$post['uid']));?></p>
                <p class="help-block"><?php echo $post['action'];?><?php echo CHtml::link($post['data']['authorName'],array('author/view','id'=>$post['data']['aid']));?></p>
                <div class="media">
                    <div class="media-left">
                        <img class="media-object lazy" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $post['data']['avatar'];?>" alt="<?php echo $post['data']['authorName'];?>">   
                    </div>
                    <div class="media-body">
                        <p class="title"><?php echo CHtml::link($post['data']['authorName'],array('author/view','id'=>$post['data']['aid']));?></p>
                        <p class="help-block"><?php echo $post['data']['content'];?></p>                        
                    </div>
                </div>
                <p class="help-block"><?php echo zmf::formatTime($post['cTime']);?></p>
            </div>
        </div>
        <?php }elseif($post['classify']=='favoriteBook'){?>
        <div class="media user-log-item">
            <div class="media-left">
                <a href="<?php echo Yii::app()->createUrl('user/index',array('id'=>$post['uid']));?>" title="<?php echo $post['truename'];?>">
                    <img class="media-object lazy a48 img-circle" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $post['avatar'];?>" alt="<?php echo $post['truename'];?>">
                </a>
            </div>
            <div class="media-body">
                <p><?php echo CHtml::link($post['truename'],array('user/index','id'=>$post['uid']));?></p>
                <p class="help-block"><?php echo $post['action'];?><?php echo CHtml::link($post['data']['bTitle'],array('book/view','id'=>$post['data']['bid']));?></p>
                <div class="media">
                    <div class="media-left">
                        <img class="media-object lazy" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $post['data']['bFaceImg'];?>" alt="<?php echo $post['data']['bTitle'];?>">                    
                    </div>
                    <div class="media-body">
                        <p class="title"><?php echo CHtml::link($post['data']['bTitle'],array('book/view','id'=>$post['data']['bid']));?></p>
                        <p class="help-block"><?php echo $post['data']['bDesc'];?></p>                        
                    </div>
                </div>
                <p class="help-block"><?php echo zmf::formatTime($post['cTime']);?></p>
            </div>            
        </div>
        <?php }elseif($post['classify']=='chapterTip'){?>
        <div class="media user-log-item">
            <div class="media-left">
                <a href="<?php echo Yii::app()->createUrl('user/index',array('id'=>$post['uid']));?>" title="<?php echo $post['truename'];?>">
                    <img class="media-object lazy a48 img-circle" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $post['avatar'];?>" alt="<?php echo $post['truename'];?>">
                </a>
            </div>
            <div class="media-body">
                <p><?php echo CHtml::link($post['truename'],array('user/index','id'=>$post['uid']));?></p>
                <p class="help-block"><?php echo $post['action'];?>【<?php echo CHtml::link($post['data']['bTitle'],array('book/view','id'=>$post['data']['bid']));?>】的<?php echo CHtml::link($post['data']['cTitle'],array('book/chapter','cid'=>$post['data']['cid']));?></p>
                <div class="media">
                    <div class="media-left">
                        <img class="media-object lazy" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $post['data']['bFaceImg'];?>" alt="<?php echo $post['data']['bTitle'];?>">                    
                    </div>
                    <div class="media-body">
                        <p class="title"><?php echo CHtml::link($post['data']['bTitle'],array('book/view','id'=>$post['data']['bid']));?></p>
                        <p class="help-block"><?php echo $post['data']['bDesc'];?></p>                        
                    </div>
                </div>
                <p class="help-block"><?php echo zmf::formatTime($post['cTime']);?></p>
            </div>
        </div>
        <?php }elseif($post['classify']=='post'){?>
        <div class="media user-log-item">
            <div class="media-left">
                <a href="<?php echo Yii::app()->createUrl('user/index',array('id'=>$post['uid']));?>" title="<?php echo $post['truename'];?>">
                    <img class="media-object lazy a48 img-circle" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $post['avatar'];?>" alt="<?php echo $post['truename'];?>">
                </a>
            </div>
            <div class="media-body">
                <p><?php echo CHtml::link($post['truename'],array('user/index','id'=>$post['uid']));?></p>
                <p class="help-block"><?php echo $post['action'];?><span class="title"><?php echo CHtml::link($post['data']['title'],array('posts/view','id'=>$post['data']['id']));?></span></p>
                <p class="help-block"><?php echo zmf::formatTime($post['cTime']);?></p>
            </div>
        </div>
        <?php }?>
        <?php }?>
        <?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
        <?php }else{?>
        <p class="color-grey text-center">他很懒，什么动态也没有！</p>
        <?php }?>
    </div>
</div>