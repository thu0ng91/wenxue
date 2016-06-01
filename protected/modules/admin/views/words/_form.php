<?php
/**
 * @filename WordsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-06-01 05:28:02 
 */

 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'words-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'word'); ?>
        <?php echo $form->textField($model,'word',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'word'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'type'); ?>
        <?php echo $form->dropDownlist($model,'type',  Words::exTypes('admin'),array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'type'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->