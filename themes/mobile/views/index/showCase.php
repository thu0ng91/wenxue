<?php
/**
 * @filename showCase.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-19  14:28:18 
 */
$postInfo=$caseInfo['post'][0];
?>
<div class="showcase">
    <?php echo CHtml::link(CHtml::image(zmf::lazyImg(), $postInfo['title'],array('data-original'=>$postInfo['faceimg'],'class'=>'lazy')),$postInfo['url'] ? $postInfo['url'] : 'javascript:;');?>
</div>