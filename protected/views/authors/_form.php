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
        <?php echo $form->textField($model,'uid'); ?>
        <?php echo $form->error($model,'uid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'authorName'); ?>
        <?php echo $form->textField($model,'authorName',array('size'=>16,'maxlength'=>16)); ?>
        <?php echo $form->error($model,'authorName'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'avatar'); ?>
        <?php echo $form->textField($model,'avatar',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'avatar'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password',array('size'=>32,'maxlength'=>32)); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'hashCode'); ?>
        <?php echo $form->textField($model,'hashCode',array('size'=>6,'maxlength'=>6)); ?>
        <?php echo $form->error($model,'hashCode'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'content'); ?>
        <?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'content'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'favors'); ?>
        <?php echo $form->textField($model,'favors'); ?>
        <?php echo $form->error($model,'favors'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'posts'); ?>
        <?php echo $form->textField($model,'posts'); ?>
        <?php echo $form->error($model,'posts'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'hits'); ?>
        <?php echo $form->textField($model,'hits'); ?>
        <?php echo $form->error($model,'hits'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'score'); ?>
        <?php echo $form->textField($model,'score'); ?>
        <?php echo $form->error($model,'score'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'cTime'); ?>
        <?php echo $form->textField($model,'cTime'); ?>
        <?php echo $form->error($model,'cTime'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'status'); ?>
        <?php echo $form->textField($model,'status'); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->