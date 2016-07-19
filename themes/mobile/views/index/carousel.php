<?php

/**
 * @filename carousel.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-19  13:50:35 
 */
$uuid=  uniqid();
$_posts1=$moduleInfo['posts'];$_len1=count($_posts1);
?>
<?php if(!empty($_posts1)){?>
<div class="module">
    <div class="module-header">
        <?php echo $moduleInfo['title'];?>
    </div>
    <div class="module-body carousel-body">
        <ul class="ui-list ui-list-link ui-border-tb">
            <?php foreach ($_posts1 as $_post){?>
            <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('book/view',array('id'=>$_post['id']));?>">
                <div class="ui-list-img">
                    <span style="background-image:url(<?php echo $_post['faceImg'];?>)"></span>
                </div>
                <div class="ui-list-info">
                    <h4 class="ui-nowrap"><?php echo $_post['title'];?></h4>
                    <p class="ui-nowrap"><?php echo $_post['authorName'];?></p>
                </div>
            </li>
            <?php }?>
        </ul>
    </div>
</div>
<?php }?>