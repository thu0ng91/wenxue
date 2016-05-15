<?php
/**
 * @filename TipsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-14 20:42:39 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tips-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'uid'); ?>
        <?php echo $form->textField($model,'uid',array('size'=>11,'maxlength'=>11)); ?>
        <?php echo $form->error($model,'uid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'logid'); ?>
        <?php echo $form->textField($model,'logid',array('size'=>11,'maxlength'=>11)); ?>
        <?php echo $form->error($model,'logid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'classify'); ?>
        <?php echo $form->textField($model,'classify',array('size'=>16,'maxlength'=>16)); ?>
        <?php echo $form->error($model,'classify'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'tocommentid'); ?>
        <?php echo $form->textField($model,'tocommentid',array('size'=>11,'maxlength'=>11)); ?>
        <?php echo $form->error($model,'tocommentid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'content'); ?>
        <?php echo $form->textField($model,'content',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'content'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'platform'); ?>
        <?php echo $form->textField($model,'platform',array('size'=>16,'maxlength'=>16)); ?>
        <?php echo $form->error($model,'platform'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'score'); ?>
        <?php echo $form->textField($model,'score',array('size'=>11,'maxlength'=>11)); ?>
        <?php echo $form->error($model,'score'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'status'); ?>
        <?php echo $form->textField($model,'status'); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'cTime'); ?>
        <?php echo $form->textField($model,'cTime',array('size'=>11,'maxlength'=>11)); ?>
        <?php echo $form->error($model,'cTime'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'ip'); ?>
        <?php echo $form->textField($model,'ip',array('size'=>16,'maxlength'=>16)); ?>
        <?php echo $form->error($model,'ip'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'ipInfo'); ?>
        <?php echo $form->textField($model,'ipInfo',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'ipInfo'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->