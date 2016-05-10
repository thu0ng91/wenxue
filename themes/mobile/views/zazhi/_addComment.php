<div id="replyoneHolder-<?php echo $keyid;?>" class="replyoneHolder"></div>
<div class="form-group">
    <?php echo CHtml::textArea('content-'.$type.'-'.$keyid,'',array('class'=>'form-control comment-textarea','maxLength'=>255,'placeholder'=>'撰写评论'));?>
</div>
<?php if(!$this->uid){?>
<div class="form-group toggle-area">    
    <div class="row">
        <div class="col-xs-6 col-sm-6">
            <input type="text" class="form-control" placeholder="如何称呼" id="<?php echo 'username-'.$type.'-'.$keyid;?>" value="<?php echo zmf::getCookie('noLoginUsername');?>">
        </div>
        <div class="col-xs-6 col-sm-6">
            <input type="text" class="form-control" placeholder="Email地址（选填）" id="<?php echo 'email-'.$type.'-'.$keyid;?>"  value="<?php echo zmf::getCookie('noLoginEmail');?>">
        </div>
    </div>
    <p class="help-block">不想等待审核？欢迎<?php echo CHtml::link('登录',array('site/login'),array('data-ajax'=>'false'));?>或<?php echo CHtml::link('注册',array('site/reg'),array('data-ajax'=>'false'));?></p>
</div>
<?php }?>
<div class="form-group toggle-area">
    <?php echo CHtml::link('评论','javascript:;',array('class'=>'btn btn-primary btn-block','action'=>'add-comment','action-data'=>$keyid,'action-type'=>$type));?>    
</div>