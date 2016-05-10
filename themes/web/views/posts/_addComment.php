<div id="replyoneHolder-<?php echo $keyid;?>" class="replyoneHolder"></div>
<div class="form-group">    
    <?php echo CHtml::textArea('content-'.$type.'-'.$keyid,'',array('class'=>'form-control comment-textarea','action'=>'comment','rows'=>3,'maxLength'=>255,'placeholder'=>'撰写评论'));?>
</div>
<?php if(!$this->uid){?>
<div class="form-group toggle-area">
    <div class="row">
        <div class="col-xs-6 col-sm-6">
            <div class="input-group">
                <span class="input-group-addon">称呼</span>
                <input type="text" class="form-control" placeholder="如何称呼" id="<?php echo 'username-'.$type.'-'.$keyid;?>" value="<?php echo zmf::getCookie('noLoginUsername');?>">
            </div>
            <p class="help-block">将显示为由谁评论</p>
        </div>
        <div class="col-xs-6 col-sm-6">
            <div class="input-group">
                <span class="input-group-addon">邮箱</span>
                <input type="text" class="form-control" placeholder="Email地址（选填）" id="<?php echo 'email-'.$type.'-'.$keyid;?>" value="<?php echo zmf::getCookie('noLoginEmail');?>">
            </div>
            <p class="help-block">Email不会被公布，仅用于接收评论的回复</p>
        </div>
    </div>
</div>
<?php }?>
<div class="form-group toggle-area">
    <p>
        <?php echo CHtml::link('评论','javascript:;',array('class'=>'btn btn-success pull-right','action'=>'add-comment','action-data'=>$keyid,'action-type'=>$type));?>
    </p>
</div>
<div class="clearfix"></div>