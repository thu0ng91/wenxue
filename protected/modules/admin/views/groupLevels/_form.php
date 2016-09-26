<?php
/**
 * @filename GroupLevelsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-25 22:55:01 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'group-levels-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'gid'); ?>        
        <?php echo $form->dropDownlist($model,'gid',  Group::listAll(),array('class'=>'form-control','empty'=>'--请选择--')); ?>
        <?php echo $form->error($model,'gid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'minExp'); ?>        
        <?php echo $form->numberField($model,'minExp',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'minExp'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'maxExp'); ?>        
        <?php echo $form->numberField($model,'maxExp',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'maxExp'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'title'); ?>        
        <?php echo $form->textField($model,'title',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'desc'); ?>        
        <?php echo $form->textField($model,'desc',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'desc'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'icon'); ?>        
        <?php echo $form->textField($model,'icon',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'icon'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->