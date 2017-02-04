<?php

/**
 * @filename setting.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-13  16:25:00 
 */
?>
<div class="module">
    <?php if($action=='baseInfo'){?>
    <div class="module-header">修改基本信息</div>
    <div class="module-body">
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'users-create-form',
                'enableAjaxValidation'=>false,
        )); ?>
        <?php echo CHtml::hiddenField('action','baseInfo');?>
        <div class="form-group">
            <?php echo $form->labelEx($model,'truename'); ?>
            <?php echo $form->textField($model,'truename',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'truename'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'content'); ?>
            <?php echo $form->textArea($model,'content',array('class'=>'form-control','rows'=>6,'maxLength'=>255)); ?>
            <?php echo $form->error($model,'content'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'sex'); ?>
            <?php echo $form->dropdownList($model,'sex',  Users::userSex('admin'),array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'sex'); ?>
        </div>
        <div class="form-group text-right">
            <?php echo CHtml::submitButton($model->isNewRecord ? '提交' : '更新',array('class'=>'btn btn-primary')); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div>
    <?php }?>
    <?php if($action=='passwd'){?>
    <div class="module-header">修改密码</div>
    <div class="module-body">
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'users-create-form',
                'enableAjaxValidation'=>false,
        )); ?>
        <?php echo CHtml::hiddenField('action','passwd');?>
        <div class="form-group">
            <label>请输入原始密码</label>
            <?php echo $form->passwordField($model,'password',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>
        <div class="form-group">
            <label>请输入新密码</label>
            <?php echo $form->passwordField($model,'newPassword',array('class'=>'form-control')); ?>
            <p class="help-block">密码不能短于6位</p>
            <?php echo $form->error($model,'newPassword'); ?>
        </div>
        <div class="form-group text-right">
            <?php echo CHtml::submitButton($model->isNewRecord ? '提交' : '更新',array('class'=>'btn btn-primary')); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div>
    <?php }?>
    <?php if($action=='skin'){?>
    <div class="module-header">更换头像</div>
    <div class="module-body">
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'users-create-form',
                'enableAjaxValidation'=>false,
        )); ?>
        <?php echo CHtml::hiddenField('action','skin');?>
        <?php echo $form->hiddenField($model,'avatar'); ?>
        <div class="form-group change-avatar-holder">
            <div class="media">
                <div class="media-left">
                    <img src="<?php echo zmf::getThumbnailUrl($model->avatar, 'a120', 'avatar');?>" alt="修改头像" id="user-avatar">
                </div>
                <div class="media-body">
                    <p class="hidden"><a href="javascript:;" class="btn btn-default openGallery" role="button" data-holder="user-avatar" data-field="<?php echo CHtml::activeId($model, 'avatar');?>">从相册选</a></p>
                    <?php $this->renderPartial('//common/_singleUpload',array('model'=>$model,'fieldName'=>'avatar','type'=>'author','fileholder'=>'filedata','targetHolder'=>'user-avatar','imgsize'=>'a120','width'=>82));?>
                    <p class="help-block">更换图片后请点击更新按钮保存设置</p>
                    <p><?php echo CHtml::submitButton($model->isNewRecord ? '提交' : '更新',array('class'=>'btn btn-primary')); ?></p>
                    <?php echo $form->error($model,'avatar'); ?>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
    <?php }?>
    
    <?php if($action=='checkPhone'){?>
    <div class="module-header">验证手机号</div>
    <div class="module-body" id="send-sms-form">
        <div class="form-group">
            <label>手机号</label>
            <div class="input-group">
                <input type="text" class="form-control" value="<?php echo zmf::hideWord($model->phone);?>" <?php if($model->phone!='') {?>disabled="disabled"<?php }else{?>placeholder="请输入常用的手机号"<?php }?> id="user-phone"/>
                <span class="input-group-btn">
                    <button class="btn btn-default sendSms-btn" type="button"  data-target="user-phone" data-type="checkPhone">发送验证码</button>
                </span>
            </div><!-- /input-group -->            
        </div>
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control bitian" placeholder="验证码" id="verifycode">
                <span class="input-group-btn">
                    <button class="btn btn-primary nextStep-btn" type="button" data-type="checkPhone" data-target="user-phone">验证手机号</button>
                </span>
            </div><!-- /input-group -->
        </div>
    </div>
    <?php }?>
</div>