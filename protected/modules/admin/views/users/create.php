<?php $this->renderPartial('/users/_nav');?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-create-form',
	'enableAjaxValidation'=>false,
)); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'truename'); ?>
        <?php echo $form->textField($model,'truename',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'truename'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'password'); ?>
        <p class="help-block">不修改密码则不用管</p>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'contact'); ?>
        <?php echo $form->textField($model,'contact',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'contact'); ?>
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
    <div class="form-group">
        <?php echo $form->labelEx($model,'isAdmin'); ?>
        <?php echo $form->dropdownList($model,'isAdmin',  Users::isAdmin('admin'),array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'isAdmin'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'status'); ?>
        <?php echo $form->dropdownList($model,'status',  Users::userStatus('admin'),array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '提交' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>

</div><!-- form -->