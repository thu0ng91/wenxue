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
<div class="container">
    <div class="main-part">
        <div class="module">
            <div class="module-header"><?php echo $info['title'];?></div>
            <div class="module-body padding-body">
                <?php echo zmf::text(array(), $info['content'],true,'w600'); ?>
            </div>
        </div>
    </div>
    <?php if(!empty($allInfos)){?>
    <div class="aside-part">
        <div class="module">
            <div class="module-header">更多文档</div>
            <div class="module-body">
                <?php foreach ($allInfos as $val){?>
                <p><?php echo CHtml::link($val['title'],array('site/info','code'=>$val['code']));?></p>
                <?php }?>
            </div>
        </div>
        <?php }?>
    </div>
</div>
