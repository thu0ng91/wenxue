<?php $uploadurl=Yii::app()->createUrl('attachments/upload',array('type'=>'siteinfo','imgsize'=>600));?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'site-info-form',
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($model); ?>	
	<div class="form-group">
            <?php echo $form->labelEx($model,'title'); ?>
            <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
            <?php echo $form->error($model,'title'); ?>
	</div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'code'); ?>
            <?php echo $form->dropDownList($model,'code',  SiteInfo::exTypes('admin'),array('class'=>'form-control','empty'=>'--请选择--')); ?>
            <?php echo $form->error($model,'code'); ?>
            <p class="help-block">每个主旨只能存在一篇文章</p>
	</div>
	<div class="form-group">
            <?php echo $form->labelEx($model,'content'); ?>
            <?php $this->renderPartial('//common/editor_um', array('model' => $model,'content' => $model->content,'uploadurl'=>$uploadurl)); ?>
            <?php echo $form->error($model,'content'); ?>
	</div>

	<div class="form-group">
            <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-success','id'=>'add-post-btn')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->