<?php
/**
 * @filename BooksController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 22:25:56 
 */
$this->renderPartial('/posts/_nav');
 ?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'books-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'uid'); ?>
        <?php echo $form->textField($model,'uid',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'uid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'aid'); ?>
        <?php echo $form->textField($model,'aid',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'aid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'colid'); ?>
        <?php echo $form->dropDownlist($model,'colid',  Column::allCols(),array('class'=>'form-control','empty'=>'--请选择--')); ?>
        <?php echo $form->error($model,'colid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'faceImg'); ?>
        <?php echo $form->textField($model,'faceImg',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'faceImg'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'desc'); ?>
        <?php echo $form->textField($model,'desc',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'desc'); ?>
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