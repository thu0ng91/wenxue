<?php
/**
 * @filename _comment.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  17:16:29 
 */
$_uname='';
if($data['uid']){
    $_uname=$data['loginUsername'];
}else{
    $_uname=$data['username'];
}
if(!$_uname){
    $_uname='匿名网友';
}
?>
<div class="media">
    <div class="media-body">
        <p><span class="comment-author"><?php echo CHtml::encode($_uname);?></span><?php if($data['tocommentid']>0 && !empty($data['toUserInfo'])){?> 回复 <b><?php echo $data['toUserInfo']['truename'];?></b><?php }?></p>
        <p><?php echo CHtml::encode($data['content']); ?></p>
        <p class="help-block">
            <?php echo zmf::formatTime($data['cTime']); ?>
            <?php if($this->uid){?>
            <span class="comment-actions">
            <?php echo $this->uid!=$data['uid'] ? CHtml::link('回复','javascript:;',array('onclick'=>"replyOne('".$data['id']."','".$data['logid']."','".$_uname."')")) : '';?>
            </span>
            <?php }?>
        </p>
    </div>
</div>