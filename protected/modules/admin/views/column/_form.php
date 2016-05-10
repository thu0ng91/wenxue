<?php
/**
 * @filename ColumnController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 16:32:06 */
$this->renderPartial('/posts/_nav');
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'column-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title',array('size'=>32,'maxlength'=>32,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'title'); ?>
    </div> 
    <div class="form-group">
        <?php echo $form->labelEx($model,'classify'); ?>
        <?php echo $form->dropDownlist($model,'classify',  Column::classify('admin'),array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'classify'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->