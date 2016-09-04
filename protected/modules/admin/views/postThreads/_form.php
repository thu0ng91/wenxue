<?php
/**
 * @filename PostThreadsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-04 22:17:36 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'post-threads-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'fid'); ?>
        
        <?php echo $form->textField($model,'fid',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'fid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'type'); ?>
        
        <?php echo $form->textField($model,'type',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'type'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'uid'); ?>
        
        <?php echo $form->textField($model,'uid',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'uid'); ?>
    </div>
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
        <?php echo $form->labelEx($model,'hits'); ?>
        
        <?php echo $form->textField($model,'hits',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'hits'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'posts'); ?>
        
        <?php echo $form->textField($model,'posts',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'posts'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'comments'); ?>
        
        <?php echo $form->textField($model,'comments',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'comments'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'favorites'); ?>
        
        <?php echo $form->textField($model,'favorites',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'favorites'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'styleStatus'); ?>
        
        <?php echo $form->textField($model,'styleStatus',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'styleStatus'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'digest'); ?>
        
        <?php echo $form->textField($model,'digest',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'digest'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'top'); ?>
        
        <?php echo $form->textField($model,'top',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'top'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'open'); ?>
        
        <?php echo $form->textField($model,'open',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'open'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'display'); ?>
        
        <?php echo $form->textField($model,'display',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'display'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'lastpost'); ?>
        
        <?php echo $form->textField($model,'lastpost',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'lastpost'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'lastposter'); ?>
        
        <?php echo $form->textField($model,'lastposter',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'lastposter'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'cTime'); ?>
        
        <?php echo $form->textField($model,'cTime',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'cTime'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->