<?php
/**
 * @filename GoodsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-10 16:12:23 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'goods-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
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
        <?php echo $form->labelEx($model,'scorePrice'); ?>        
        <?php echo $form->textField($model,'scorePrice',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'scorePrice'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'goldPrice'); ?>        
        <?php echo $form->textField($model,'goldPrice',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'goldPrice'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'content'); ?>        
        <?php echo $form->textField($model,'content',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'content'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'classify'); ?>        
        <?php echo $form->textField($model,'classify',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'classify'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->