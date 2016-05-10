<div id="replyoneHolder-<?php echo $keyid;?>" class="replyoneHolder"></div>
<div class="form-group">
    <?php echo CHtml::textArea('content-'.$type.'-'.$keyid,'',array('class'=>'form-control comment-textarea','maxLength'=>255,'placeholder'=>'撰写评论'));?>
</div>
<?php if(!$this->uid){?>
<div class="form-group toggle-area">    
    <div class="ui-row-flex">
        <div class="ui-col">
            <input type="text" class="form-control" placeholder="如何称呼" id="<?php echo 'username-'.$type.'-'.$keyid;?>" value="<?php echo zmf::getCookie('noLoginUsername');?>">
        </div>
        <div class="ui-col">
            <input type="text" class="form-control" placeholder="Email地址（选填）" id="<?php echo 'email-'.$type.'-'.$keyid;?>"  value="<?php echo zmf::getCookie('noLoginEmail');?>">
        </div>
    </div>
</div>
<?php }?>
<div class="ui-btn-wrap toggle-area">
    <?php echo CHtml::link('评论','javascript:;',array('class'=>'ui-btn-lg','action'=>'add-comment','action-data'=>$keyid,'action-type'=>$type));?>    
</div>