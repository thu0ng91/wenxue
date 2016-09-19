<?php
/**
 * @filename OrdersController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-19 10:16:06 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'orders-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'orderId'); ?>
        
        <?php echo $form->textField($model,'orderId',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'orderId'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'uid'); ?>
        
        <?php echo $form->textField($model,'uid',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'uid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'gid'); ?>
        
        <?php echo $form->textField($model,'gid',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'gid'); ?>
    </div>
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
        <?php echo $form->labelEx($model,'faceUrl'); ?>
        
        <?php echo $form->textField($model,'faceUrl',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'faceUrl'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'classify'); ?>
        
        <?php echo $form->textField($model,'classify',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'classify'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'content'); ?>
        
        <?php echo $form->textField($model,'content',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'content'); ?>
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
        <?php echo $form->labelEx($model,'num'); ?>
        
        <?php echo $form->textField($model,'num',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'num'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'payAction'); ?>
        
        <?php echo $form->textField($model,'payAction',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'payAction'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'orderStatus'); ?>
        
        <?php echo $form->textField($model,'orderStatus',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'orderStatus'); ?>
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
        <?php echo $form->labelEx($model,'paidTime'); ?>
        
        <?php echo $form->textField($model,'paidTime',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'paidTime'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'paidOrderId'); ?>
        
        <?php echo $form->textField($model,'paidOrderId',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'paidOrderId'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'paidType'); ?>
        
        <?php echo $form->textField($model,'paidType',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'paidType'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->