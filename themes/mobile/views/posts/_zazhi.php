<?php

/**
 * @filename _zazhi.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-19  10:32:22 
 */
$url=Yii::app()->createUrl('zazhi/view',array('zid'=>$data['id']));
?>
<li class="ui-border-t" data-href="<?php echo $url;?>">
    <div class="ui-list-img">
        <span style="background-image:url(<?php echo $data['faceimg'];?>)"></span>
    </div>
    <div class="ui-list-info">
        <h4 class="ui-nowrap"><?php echo $data['title'];?></h4>
        <p class="ui-nowrap"><?php echo $data['content'];?></p>
    </div>
</li>