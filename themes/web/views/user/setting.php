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
                    <img src="<?php echo $model->avatar;?>" alt="修改头像" id="user-avatar">
                </div>
                <div class="media-body">
                    <p><a href="javascript:;" class="btn btn-default openGallery" role="button" data-holder="user-avatar" data-field="<?php echo CHtml::activeId($model, 'avatar');?>">选择头像</a></p>
                    <p class="help-block">更换图片后请点击更新按钮保存设置</p>
                    <p><?php echo CHtml::submitButton($model->isNewRecord ? '提交' : '更新',array('class'=>'btn btn-primary')); ?></p>
                    <?php echo $form->error($model,'avatar'); ?>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>