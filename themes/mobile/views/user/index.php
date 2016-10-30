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
    <div class="module-header">动态</div>
    <div class="module-body">
        <?php if(!empty($posts)){?>
        <ul class="ui-list ui-border-b">
        <?php foreach ($posts as $post){?>
        <?php if($post['classify']=='favoriteAuthor'){?>
            <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('author/view',array('id'=>$post['data']['aid']));?>">
                <div class="ui-list-img">
                    <span style="background-image:url(<?php echo $post['data']['avatar'];?>)"></span>
                </div>
                <div class="ui-list-info">
                    <p class="ui-nowrap title"><?php echo $post['truename'];?></p>
                    <p class="ui-nowrap"><?php echo $post['action'];?><?php echo $post['data']['authorName'];?></p>
                    <p class="ui-nowrap color-grey">简介：<?php echo $post['data']['content'];?></p>
                    <p class="help-block"><?php echo zmf::formatTime($post['cTime']);?></p>
                </div>
            </li>
        <?php }elseif($post['classify']=='favoriteBook'){?>
            <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('book/view',array('id'=>$post['data']['bid']));?>">
                <div class="ui-list-img">
                    <span style="background-image:url(<?php echo $post['data']['bFaceImg'];?>)"></span>
                </div>
                <div class="ui-list-info">
                    <p class="ui-nowrap title"><?php echo $post['truename'];?></p>
                    <p class="ui-nowrap"><?php echo $post['action'];?><?php echo $post['data']['bTitle'];?></p>
                    <p class="ui-nowrap"><?php echo $post['data']['bDesc'];?></p>
                    <p class="help-block"><?php echo zmf::formatTime($post['cTime']);?></p>
                </div>
            </li>
        <?php }elseif($post['classify']=='chapterTip'){?>
            <li class="ui-border-t">
                <div class="ui-list-img">
                    <span style="background-image:url(<?php echo $post['data']['bFaceImg'];?>)"></span>
                </div>
                <div class="ui-list-info">
                    <p class="ui-nowrap title"><?php echo $post['truename'];?></p>
                    <p class="ui-nowrap-multi"><?php echo $post['action'];?>【<?php echo CHtml::link($post['data']['bTitle'],array('book/view','id'=>$post['data']['bid']));?>】的<?php echo CHtml::link($post['data']['cTitle'],array('book/chapter','cid'=>$post['data']['cid']));?></p>
                    <p class="help-block"><?php echo zmf::formatTime($post['cTime']);?></p>
                </div>
            </li>
        <?php }elseif($post['classify']=='post'){?>
            <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('posts/view',array('id'=>$post['data']['id']));?>">
                <div class="ui-list-info">
                    <p class="ui-nowrap title"><?php echo $post['truename'];?></p>
                    <p class="ui-nowrap"><?php echo $post['action'];?><?php echo $post['data']['title'];?></p>
                    <p class="help-block"><?php echo zmf::formatTime($post['cTime']);?></p>
                </div>
            </li>
        <?php }?>
        <?php }?>        
        </ul>
        <?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
        <?php }else{?>
        <p class="color-grey text-center">他很懒，什么动态也没有！</p>
        <?php }?>
    </div>
</div>