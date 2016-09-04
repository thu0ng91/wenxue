<?php
/**
 * @filename GroupController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:20:35 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'group-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'title'); ?>        
        <?php echo $form->textField($model,'title',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'faceImg'); ?>        
        <?php echo $form->textField($model,'faceImg',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'faceImg'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'desc'); ?>        
        <?php echo $form->textArea($model,'desc',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'desc'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'status'); ?>        
        <?php echo $form->dropDownlist($model,'status',  zmf::yesOrNo('admin'),array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->