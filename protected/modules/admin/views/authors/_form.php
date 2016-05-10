<?php
/**
 * @filename AuthorsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 17:20:08 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'authors-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'uid'); ?>
        <?php echo $form->textField($model,'uid',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'uid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'authorName'); ?>
        <?php echo $form->textField($model,'authorName',array('size'=>16,'maxlength'=>16,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'authorName'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'avatar'); ?>
        <?php echo $form->textField($model,'avatar',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'avatar'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password',array('size'=>32,'maxlength'=>32,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'content'); ?>
        <?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'content'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->