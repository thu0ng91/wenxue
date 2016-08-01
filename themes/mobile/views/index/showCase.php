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
if(!empty($postInfo)){?>
<a href="<?php echo $postInfo['url'] ? $postInfo['url'] : 'javascript:;';?>" title="<?php echo $postInfo['title'];?>">
    <div class="showcase" style="background-image: url(<?php echo $postInfo['faceimg'];?>)"></div>
</a>
<?php }