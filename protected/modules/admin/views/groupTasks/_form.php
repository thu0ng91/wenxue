<?php
/**
 * @filename GroupTasksController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:21:04 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'group-tasks-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'gid'); ?>
        
        <?php echo $form->textField($model,'gid',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'gid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'tid'); ?>
        
        <?php echo $form->textField($model,'tid',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'tid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'action'); ?>
        
        <?php echo $form->textField($model,'action',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'action'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'type'); ?>
        
        <?php echo $form->textField($model,'type',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'type'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'num'); ?>
        
        <?php echo $form->textField($model,'num',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'num'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'score'); ?>
        
        <?php echo $form->textField($model,'score',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'score'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'startTime'); ?>
        
        <?php echo $form->textField($model,'startTime',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'startTime'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'endTime'); ?>
        
        <?php echo $form->textField($model,'endTime',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'endTime'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'times'); ?>
        
        <?php echo $form->textField($model,'times',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'times'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->