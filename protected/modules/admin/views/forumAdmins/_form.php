<?php
/**
 * @filename ForumAdminsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-10-21 09:25:32 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'forum-admins-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'fid'); ?>
        <?php echo $model->id ? $form->dropDownlist($model,'fid', PostForums::listAll(),array('class'=>'form-control')) :$form->dropDownlist($model,'fid', PostForums::listAll(),array('class'=>'form-control','multiple'=>'true')); ?>
        <?php echo $form->error($model,'fid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'uid'); ?>        
        <?php echo $form->textField($model,'uid',array('class'=>'form-control')); ?>
        <p class="help-block">请填入要被设置为版主的用户ID</p>
        <?php echo $form->error($model,'uid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'num'); ?>        
        <?php echo $form->textField($model,'num',array('class'=>'form-control')); ?>
        <p class="help-block">该用户在该版块最大可发帖数量，0表示不限</p>
        <?php echo $form->error($model,'num'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'powers'); ?>        
        <?php echo $form->dropDownlist($model,'powers', UserAction::userActions('post'),array('class'=>'form-control','empty'=>'--请选择--','multiple'=>'true','SIZE'=>8)); ?>
        <p class="help-block">该用户在该版块可进行的操作，不包含该用户自身拥有的权限</p>
        <?php echo $form->error($model,'powers'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->