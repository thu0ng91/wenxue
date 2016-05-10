<?php
/* @var $this ZazhiController */
/* @var $model Zazhi */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'zazhi-form',
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($model); ?>
	<div class="form-group">
            <?php echo $form->labelEx($model,'title'); ?>
            <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
            <?php echo $form->error($model,'title'); ?>
	</div>
    
        <div class="form-group">
            <?php echo $form->labelEx($model,'faceimg'); $faceimg=  Attachments::faceImg($model, '960', 'zazhi');?>
            <div id="fileSuccess" class="fileSuccess">
                <?php if($faceimg){?>
                <p><img src="<?php echo $faceimg;?>" class="img-responsive"/></p>
                <?php }?>
            </div>
            <div id="singleFileQueue" style="clear:both;"></div>
            <div id="noModelUpload"></div>
            <?php echo $form->hiddenField($model,'faceimg'); ?>
        </div>

	<div class="form-group">
            <?php echo $form->labelEx($model,'content'); ?>
            <?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
            <?php echo $form->error($model,'content'); ?>
	</div>

	<div class="form-group">
            <?php echo $form->labelEx($model,'status'); ?>
            <?php echo $form->dropDownList($model,'status',  Posts::exStatus('admin'),array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'status'); ?>
	</div>

	<div class="form-group">
            <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/common/uploadify/jquery.uploadify.min.js', CClientScript::POS_END); ?>
<script>
    var imgUploadUrl = "<?php echo Yii::app()->createUrl('/attachments/upload', array('type' => 'zazhi')); ?>";
    $(document).ready(
    function () {
        singleUploadify({
            placeHolder:'noModelUpload',
            inputId:'<?php echo CHtml::activeId($model, 'faceimg');?>',
            limit:<?php echo isset($num) ? $num : 30; ?>,
            uploadUrl:imgUploadUrl,
            width:100,
            height:34,
            multi:false,
            type:'zazhi'
        });
    });
</script>