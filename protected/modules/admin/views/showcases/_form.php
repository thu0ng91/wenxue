<?php
/**
 * @filename ShowcasesController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-17 08:49:07 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'showcases-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title',array('size'=>16,'maxlength'=>16,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'columnid'); ?>
        <?php echo $form->dropDownlist($model,'columnid',  Column::allCols(),array('class'=>'form-control','empty'=>'--请选择--')); ?>
        <?php echo $form->error($model,'columnid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'classify'); ?>
        <?php echo $form->dropDownlist($model,'classify',  Showcases::exClassify('admin'),array('class'=>'form-control','empty'=>'--请选择--')); ?>
        <?php echo $form->error($model,'classify'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'position'); ?>
        <?php echo $form->dropDownlist($model,'position',  Showcases::exPosition('admin'),array('class'=>'form-control','empty'=>'--请选择--')); ?>
        <?php echo $form->error($model,'position'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'display'); ?>
        <?php echo $form->dropDownlist($model,'display',  Showcases::exDisplay('admin'),array('class'=>'form-control','empty'=>'--请选择--')); ?>
        <?php echo $form->error($model,'display'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'num'); ?>
        <?php echo $form->textField($model,'num',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'num'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->