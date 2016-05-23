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
    <div class="module-header">修改密码</div>
    <div class="module-body">
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'users-create-form',
                'enableAjaxValidation'=>false,
        )); ?>
        <?php echo CHtml::hiddenField('action','skin');?>
        <?php echo $form->hiddenField($model,'avatar'); ?>
        <?php echo $form->hiddenField($model,'skinUrl'); ?>
        <div class="row">
            <div class="col-sm-6 col-md-6">
                <div class="thumbnail">
                    <img src="<?php echo $model->avatar;?>" alt="修改头像" id="user-avatar">
                    <div class="caption">
                        <p class="text-center"><a href="javascript:;" class="btn btn-default openGallery" role="button" data-holder="user-avatar" data-field="<?php echo CHtml::activeId($model, 'avatar');?>">选择头像</a></p>
                    </div>
                </div>
                <?php echo $form->error($model,'avatar'); ?>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="thumbnail">
                    <img src="<?php echo $model->skinUrl;?>" alt="更改皮肤" id="user-skin">
                    <div class="caption">
                        <p class="text-center"><a href="javascript:;" class="btn btn-default openGallery" role="button"  data-holder="user-skin" data-field="<?php echo CHtml::activeId($model, 'skinUrl');?>">选择皮肤背景图片</a></p>
                    </div>
                </div>
                <?php echo $form->error($model,'skinUrl'); ?>
            </div>
        </div>
        <div class="form-group text-right">
            <?php echo CHtml::submitButton($model->isNewRecord ? '提交' : '更新',array('class'=>'btn btn-primary')); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>