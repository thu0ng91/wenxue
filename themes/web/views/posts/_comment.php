<?php
/**
 * @filename _comment.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  17:16:29 
 */
$_uname=$data['loginUsername'];
?>
<?php if($data['status']==Posts::STATUS_PASSED){?>
<div class="media" id="comment-<?php echo $data['id']; ?>">
    <div class="media-body">
        <p>
            <b><?php echo CHtml::link($_uname,array('user/index','id'=>$data['uid']));?></b>
            <?php if(!empty($data['replyInfo'])){?>
            回复 <b><?php echo CHtml::link($data['replyInfo']['truename'],array('user/index','id'=>$data['replyInfo']['uid']));?></b>        
            <?php }?>
        </p>
        <p><?php echo nl2br(CHtml::encode($data['content'])); ?></p>
        <p class="help-block">
            <?php echo zmf::formatTime($data['cTime']); ?>
            <span class="color-grey"><?php echo CHtml::link('举报','javascript:;',array('action'=>'report','action-type'=>'comment','action-id'=>$data['id'],'action-title'=>  zmf::subStr($data['content'])));?></span>
            <?php if($this->uid){?>
            <span class="pull-right">
                <?php 
                if($this->uid!=$data['uid'] && $postInfo['open']==Posts::STATUS_OPEN){
                    echo CHtml::link('回复','javascript:;',array('onclick'=>"replyOne('".$data['id']."','".$data['logid']."','".$_uname."')"));
                }elseif($this->uid==$data['uid']){
                    echo CHtml::link('删除','javascript:;',array('action'=>'delContent','data-type'=>'comment','data-id'=>  $data['id'],'data-confirm'=>1,'data-target'=>'comment-'.$data['id']));  
                }?>                
            </span>
            <?php }?>
        </p>
    </div>
</div>
<?php }else{?>
<div class="alert alert-danger">
    你的评论包含敏感词，暂不能显示。
</div>
<?php } 