<?php
/**
 * @filename PostPostsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-05 04:08:51 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'post-posts-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'uid'); ?>
        
        <?php echo $form->textField($model,'uid',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'uid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'aid'); ?>
        
        <?php echo $form->textField($model,'aid',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'aid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'tid'); ?>
        
        <?php echo $form->textField($model,'tid',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'tid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'content'); ?>
        
        <?php echo $form->textField($model,'content',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'content'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'comments'); ?>
        
        <?php echo $form->textField($model,'comments',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'comments'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'favors'); ?>
        
        <?php echo $form->textField($model,'favors',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'favors'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'cTime'); ?>
        
        <?php echo $form->textField($model,'cTime',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'cTime'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'updateTime'); ?>
        
        <?php echo $form->textField($model,'updateTime',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'updateTime'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'open'); ?>
        
        <?php echo $form->textField($model,'open',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'open'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'status'); ?>
        
        <?php echo $form->textField($model,'status',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->