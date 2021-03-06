<?php
/**
 * @filename _comment.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  17:16:29 
 */

$_uname=$data['userInfo']['username'];

?>
<?php if($data['status']==Posts::STATUS_PASSED){?>
<li class="ui-border-t" id="comment-<?php echo $data['id']; ?>">
    <?php if($data['userInfo']['avatar'] && $showAvatar){?>
    <div class="ui-avatar">
        <span style="background-image:url(<?php echo $data['userInfo']['avatar'];?>)"></span>
    </div>
    <?php }?>
    <div class="ui-list-info">
        <p>
            <b><?php echo CHtml::link($_uname,$data['userInfo']['linkArr']);?><?php if($from=='tip'){if($data['userInfo']['type']=='author'){if($data['userInfo']['id']==$bookInfo['aid']){echo '（楼主）';}}}elseif($postInfo['aid']>0){if($postInfo['aid']==$data['userInfo']['id']){echo '（楼主）';}}elseif($postInfo['uid']==$data['uid']){echo '（楼主）';} ?></b>
            <?php if(!empty($data['replyInfo'])){?>
            回复 <b><?php echo CHtml::link($data['replyInfo']['username'],$data['replyInfo']['linkArr']);?></b>        
            <?php }?>
            <span class="pull-right">
                <?php echo CHtml::link('<i class="fa '.($data['favorited'] ? 'fa-thumbs-up' : 'fa-thumbs-o-up').'"></i> '.$data['favors'],'javascript:;',array('action'=>'favorite','action-data'=>$data['id'],'action-type'=>'comment'));?>
            </span>
        </p>
        <p><?php echo nl2br(CHtml::encode($data['content'])); ?></p>
        <p class="help-block">
            <?php echo zmf::formatTime($data['cTime']); ?>
            <?php if($this->uid!=$data['uid']){?>
            <span class="color-grey"><?php echo CHtml::link('举报','javascript:;',array('action'=>'report','action-type'=>'comment','action-id'=>$data['id'],'action-title'=>  zmf::subStr($data['content'])));?></span>
            <?php }?>
            <?php if($this->uid){?>
            <span class="pull-right">
                <?php 
                if($this->uid!=$data['uid']){
                    if($from=='tip'){
                        echo CHtml::link('回复','javascript:;',array('onclick'=>"replyOne('".$data['id']."','".$data['logid']."','".$_uname."')"));
                    }elseif($postInfo['open']==Posts::STATUS_OPEN){
                        echo CHtml::link('回复','javascript:;',array('onclick'=>"replyOne('".$data['id']."','".$data['logid']."','".$_uname."')"));
                    }
                }elseif($this->uid==$data['uid']){
                    echo CHtml::link('删除','javascript:;',array('action'=>'delContent','data-type'=>'comment','data-id'=>  $data['id'],'data-confirm'=>1,'data-target'=>'comment-'.$data['id']));  
                }?>                
            </span>
            <?php }?>
        </p>
    </div>
</li>
<?php }else{?>
<li class="ui-border-t">
    <div class="ui-list-info">        
        <p class="ui-nowrap">
            你的评论包含敏感词，暂不能显示。
        </p>
    </div>
</li>
<?php } 