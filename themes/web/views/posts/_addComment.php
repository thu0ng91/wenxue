<?php if($this->uid){?>
<div id="replyoneHolder-<?php echo $keyid;?>" class="replyoneHolder"></div>
<div class="form-group">    
    <?php echo CHtml::textArea('content-'.$type.'-'.$keyid,'',array('class'=>'form-control comment-textarea','action'=>'comment','rows'=>3,'maxLength'=>255,'placeholder'=>'撰写评论'));?>
</div>
<div class="form-group toggle-area">
    <p><?php echo CHtml::link('评论','javascript:;',array('class'=>'btn btn-success pull-right','action'=>'add-comment','action-data'=>$keyid,'action-type'=>$type));?></p>
</div>
<?php }else{?>
<p class="help-block text-center">发表评论，请先<?php echo CHtml::link('登录',array('site/login'));?>或<?php echo CHtml::link('注册',array('site/reg'));?>。</p>
<?php }?>
<div class="clearfix"></div>
