<?php if($this->uid){?>
<div id="replyoneHolder-<?php echo $keyid;?>" class="replyoneHolder"></div>
<div class="form-group">    
    <?php echo CHtml::textArea('content-'.$type.'-'.$keyid,'',array('class'=>'form-control comment-textarea','action'=>'comment','rows'=>3,'maxLength'=>255,'placeholder'=>'撰写评论'));?>
</div>
<div class="form-group toggle-area">    
    <?php if($authorPanel){?>
    <?php if($authorLogin){?>
    <div class="help-block"><?php echo CHtml::checkBox('isAuthor-'.$type.'-'.$keyid, true, array('class'=>'checkbox','id'=>'use-author-info-checkbtn')); ?><label for="use-author-info-checkbtn">使用作者身份回复</label></div>
    <?php }else{?>
    <p class="help-block"><?php echo CHtml::link('登录作者中心',array('user/authorAuth'));?>后即可使用作者身份回复</p>
    <?php }?>
    <?php }?>
</div>
<div class="form-group toggle-area">    
    <?php echo CHtml::link('评论','javascript:;',array('class'=>'btn btn-success pull-right','action'=>'add-comment','action-data'=>$keyid,'action-type'=>$type));?>    
</div>
<?php }else{?>
<p class="help-block text-center">发表评论，请先<?php echo CHtml::link('登录',array('site/login'));?>或<?php echo CHtml::link('注册',array('site/reg'));?>。</p>
<?php }?>
<div class="clearfix"></div>
