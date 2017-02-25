<?php

/**
 * @filename carousel.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-19  13:50:35 
 */
if(!empty($authors)){?>
<div class="module index-list">
    <div class="module-header">热门作者</div>
    <div class="module-body">
        <ul class="ui-list ui-list-link ui-border-t">
        <?php foreach ($authors as $k=>$author){?>
        <?php if($k==0){?>
            <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('author/view',array('id'=>$author['id']));?>">
                <div class="ui-list-img">
                    <img class="lazy w78" src="<?php echo zmf::lazyImg(); ?>" data-original="<?php echo $author['avatar']; ?>" alt="<?php echo $author['authorName']; ?>">                    
                </div>
                <div class="ui-list-info">
                    <h4 class="ui-nowrap"><?php echo $author['authorName'];?></h4>
                    <p class="ui-nowrap-multi color-grey">简介：<?php echo $author['content'];?></p>
                </div>
            </li>
        <?php continue;}?>
            <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('author/view',array('id'=>$author['id']));?>">
                <div class="ui-list-info">
                    <p class="ui-nowrap"><span class="dot"><?php echo ($k+1);?></span><?php echo $author['authorName'];?></p>
                </div>
            </li>
        <?php }?>
        </ul>
    </div>
</div>
<?php }