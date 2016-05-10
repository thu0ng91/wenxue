<?php 
$url=zmf::config('domain').Yii::app()->createUrl('posts/view',array('id'=>$data['id']));
$qrcode=  zmf::qrcode($url, 'posts', $data['id']);
?>
<li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('posts/view',array('id'=>$data['id']));?>">
    <?php if($data['faceimg']!=''){?>
    <div class="ui-list-img">
        <span style="background-image:url(<?php echo $data['faceimg'];?>)"></span>
    </div>
    <?php }?>
    <div class="ui-list-info">
        <h4 class="ui-nowrap-multi"><?php echo $data['title'];?></h4>
        <p class="ui-nowrap-multi"><?php echo zmf::subStr($data['content'],140);?></p>
    </div>
</li>