<?php
/**
 * @filename tags.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-5  15:01:54 
 */
?>
<div class="ui-container">    
    <ul class="ui-list ui-list-text ui-border-tb ui-list-link">
        <?php foreach ($posts as $post){?>
        <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('index/index',array('tagid'=>$post['id']));?>">
            <p><?php echo $post['title'];?></p>
        </li>
        <?php }?>
    </ul>
    <?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
</div>