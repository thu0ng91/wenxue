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
    <div class="module-body padding-body">
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
            <?php echo $form->textArea($model,'content',array('class'=>'form-control','rows'=>3,'maxLength'=>255)); ?>
            <?php echo $form->error($model,'content'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'sex'); ?>
            <?php echo $form->dropdownList($model,'sex',  Users::userSex('admin'),array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'sex'); ?>
        </div>
        <div class="form-group">
            <?php echo CHtml::submitButton($model->isNewRecord ? '提交' : '更新',array('class'=>'btn btn-success')); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div>
    <?php }?>
    <?php if($action=='passwd'){?>
<div class="login-reg-module">    
    <?php echo CHtml::link('<i class="fa fa-remove"></i>',$this->referer ? $this->referer : 'javascript:history.back()',array('class'=>'fixed-return-url'));?>
    <div class="login-reg-form">
        <h1><?php echo CHtml::link(zmf::config('sitename'),  zmf::config('baseurl'));?></h1>
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'users-create-form',
                'enableAjaxValidation'=>false,
        )); ?>
        <?php echo CHtml::hiddenField('action','passwd');?>
        <div class="form-group">
            <span class="fixed-label"><i class="fa fa-lock"></i></span>
            <?php echo $form->passwordField($model,'password',array('class'=>'form-control','placeholder'=>'请输入原始密码')); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>
        <div class="form-group">
            <span class="fixed-label"><i class="fa fa-lock"></i></span>
            <?php echo $form->passwordField($model,'newPassword',array('class'=>'form-control','placeholder'=>'请输入新密码（密码不能短于6位）')); ?>
            <?php echo $form->error($model,'newPassword'); ?>
        </div>
        <div class="form-group">
            <?php echo CHtml::submitButton($model->isNewRecord ? '提交' : '更新',array('class'=>'btn btn-success')); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div>
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
            <ul class="ui-list">
                <li>
                    <div class="ui-avatar">
                        <img src="<?php echo zmf::getThumbnailUrl($model->avatar, 'a120', 'avatar');?>" alt="修改头像" id="user-avatar" class="a50">
                    </div>
                    <div class="ui-list-info">
                        <?php $this->renderPartial('/common/_singleUpload',array('model'=>$model,'fieldName'=>'avatar','type'=>'author','fileholder'=>'filedata','targetHolder'=>'user-avatar','imgsize'=>'a120','progress'=>true));?>
                        <p class="help-block">更换图片后请点击更新按钮保存设置</p>
                    </div>
                </li>
            </ul>
            <div id="progress" class="progress">
                <div class="progress-bar progress-bar-success" style="width: 0%;"></div>
            </div>
        </div>
        <div class="form-group padding-body">
            <p><?php echo CHtml::submitButton($model->isNewRecord ? '提交' : '更新',array('class'=>'btn btn-success')); ?></p>
            <?php echo $form->error($model,'avatar'); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div>
    <?php }?>
    
    <?php if($action=='checkPhone'){?>
    <div class="module-header">验证手机号</div>
    <div class="module-body padding-body" id="send-sms-form">
        <div class="form-group">
            <label>手机号</label>
            <div class="input-group">
                <input type="text" class="form-control" value="<?php echo $model->phone ? zmf::hideWord($model->phone) : '';?>" <?php echo $model->phone ? 'disabled="disabled"' : '';?> id="user-phone" placeholder="请输入常用手机号"/>
                <span class="input-group-btn">
                    <button class="btn btn-default sendSms-btn" type="button"  data-target="user-phone" data-type="checkPhone">发送验证码</button>
                </span>
            </div><!-- /input-group -->            
        </div>
        <div class="form-group">
            <input type="text" class="form-control bitian" placeholder="请输入收到的验证码" id="verifycode">
        </div>
        <div class="form-group">
            <button class="btn btn-success nextStep-btn" type="button" data-type="checkPhone" data-target="user-phone">验证手机号</button>                
        </div>
    </div>
    <?php }?>
</div>