<?php

/**
 * @filename sideModule.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-19  16:03:15 
 */
$_sidePosts=$sideInfo['posts'];
if(!empty($_sidePosts)){
?>
<div class="module">
    <div class="module-header">
        <?php echo $sideInfo['title'];?>
    </div>
    <div class="module-body">
        <ul class="ui-list ui-list-link ui-border-tb">
        <?php foreach ($_sidePosts as $k=>$_post){?>
        <?php if( ($sideInfo['display']=='thumbFirst' && $k==0) || ($sideInfo['display']=='thumbThird' && $k<3) ){?>
            <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('book/view',array('id'=>$_post['id']));?>">
                <div class="ui-list-img">
                    <span style="background-image:url(<?php echo $_post['faceImg'];?>)"></span>
                </div>
                <div class="ui-list-info">
                    <h4 class="ui-nowrap"><?php echo ($_post['colTitle']!='' ? '['.$_post['colTitle'].']' : '').$_post['title'];?></h4>
                    <p class="ui-nowrap-multi"><?php echo zmf::subStr($_post['desc'],80);?></p>
                </div>
            </li>
        <?php continue;}?>
            <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('book/view',array('id'=>$_post['id']));?>">
                <div class="ui-list-info">
                    <p class="ui-nowrap"><span class="dot"><?php echo ($k+1);?></span><?php echo ($_post['colTitle']!='' ? '['.$_post['colTitle'].']' : '').$_post['title'];?></p>
                </div>
            </li>
        <?php }?>
        </ul>
    </div>
</div>
<?php }?>   