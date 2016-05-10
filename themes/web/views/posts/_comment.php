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
<div class="media" id="comment-<?php echo $data['id']; ?>">
    <div class="media-body">
        <p><b><?php echo CHtml::encode($_uname);?></b></p>
        <p><?php echo nl2br(CHtml::encode($data['content'])); ?></p>
        <p class="help-block">
            <?php echo zmf::formatTime($data['cTime']); ?>
            <?php if($this->uid){?>
            <span class="pull-right">
                <?php 
                if($this->uid!=$postInfo['uid']){
                    echo CHtml::link('回复','javascript:;',array('onclick'=>"replyOne('".$data['id']."','".$data['logid']."','".$_uname."')"));
                }
                if($this->uid==$postInfo['uid'] || $this->userInfo['isAdmin']){
                    echo CHtml::link('删除','javascript:;',array('action'=>'del-content','action-type'=>'comment','action-data'=>  $data['id'],'action-confirm'=>1,'action-target'=>'comment-'.$data['id']));  
                }?>                
            </span>
            <?php }?>
        </p>
    </div>
</div>