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
        <?php echo $form->labelEx($model,'content'); ?> 
        <?php $this->renderPartial('//common/editor_um', array('model' => $model,'content' => $model->content,'hiddenToolbar'=>true)); ?>
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
        <?php echo $form->labelEx($model,'open'); ?>        
        <?php echo $form->dropDownlist($model,'open',  zmf::yesOrNo('admin'),array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'open'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'status'); ?>        
        <?php echo $form->dropDownlist($model,'status',  Posts::exStatus('admin'),array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->