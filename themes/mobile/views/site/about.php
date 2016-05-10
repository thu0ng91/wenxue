<?php

/**
 * @filename about.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-5  15:04:55 
 */
?>
<div class="ui-container">
    <div class="module">
        <?php echo zmf::text(array(), $info['content']); ?>
    </div>
    <?php if(!empty($allInfos)){?>
    <ul class="ui-list ui-list-text ui-list-link">
    <?php foreach($allInfos as $_post){?>
        <li data-href="<?php echo Yii::app()->createUrl('site/info',array('code'=>$_post['code']));?>" class="ui-border-b">
            <p><?php echo $_post['title'];?></p>
        </li>
    <?php }?>
    </ul>
    <?php }?>
</div>