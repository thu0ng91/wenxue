<?php
/**
 * @filename WenkuColumnsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:16:42 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'wenku-columns-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'belongid'); ?>
        
        <?php echo $form->textField($model,'belongid',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'belongid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'name'); ?>
        
        <?php echo $form->textField($model,'name',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'title'); ?>
        
        <?php echo $form->textField($model,'title',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'second_title'); ?>
        
        <?php echo $form->textField($model,'second_title',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'second_title'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'classify'); ?>
        
        <?php echo $form->textField($model,'classify',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'classify'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'position'); ?>
        
        <?php echo $form->textField($model,'position',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'position'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'url'); ?>
        
        <?php echo $form->textField($model,'url',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'url'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'attachid'); ?>
        
        <?php echo $form->textField($model,'attachid',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'attachid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'order'); ?>
        
        <?php echo $form->textField($model,'order',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'order'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'hits'); ?>
        
        <?php echo $form->textField($model,'hits',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'hits'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'status'); ?>
        
        <?php echo $form->textField($model,'status',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'cTime'); ?>
        
        <?php echo $form->textField($model,'cTime',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'cTime'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'system'); ?>
        
        <?php echo $form->textField($model,'system',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'system'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->