<?php
/* @var $this ActivityController */
/* @var $model Activity */
/* @var $form CActiveForm */
?>
<style>
    #ui-datepicker-div{
        display: none
    }
</style>
<div class="form" style="width: 640px">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'activity-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
    
        <div class="form-group">
            <?php echo $form->labelEx($model,'type'); ?>
            <?php echo $form->dropDownList($model,'type', Activity::types('admin'),array('class'=>'form-control','empty'=>'--请选择--'));?>
            <?php echo $form->error($model,'type'); ?>
	</div>
    
        <div class="form-group">
            <?php echo $form->labelEx($model,'faceimg'); ?>
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
            <div class="row">
                <div class="col-xs-6 col-sm-6">
                    <?php echo $form->labelEx($model,'voteNum'); ?>
                    <?php echo $form->textField($model,'voteNum',array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'voteNum'); ?>
                </div>
                <div class="col-xs-6 col-sm-6">
                    <?php echo $form->labelEx($model,'voteType'); ?>
                    <?php echo $form->dropDownList($model,'voteType',  Activity::voteTypes('admin'),array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'voteType'); ?>
                </div>
            </div>		
	</div>

	<div class="form-group">            
            <div class="row">
                <div class="col-xs-6 col-sm-6">
                    <?php echo $form->labelEx($model,'startTime'); ?>
                    <?php 
                        $model->startTime=$model->startTime ? zmf::time($model->startTime,'Y/m/d H:i:s') : '';
                        $this->widget('ext.timepicker.timepicker', array(
                        'model'=>$model,
                        'name'=>'startTime',
                        'options'=>array(
                            'showOn'=>'focus',
                            'showAnim'=>'fadeIn',
                            'dateFormat'=>'yy/mm/dd',
                            'timeFormat'=>'hh:mm:ss',    
                        ),			    
                        ));
                    ?>               
                    <?php echo $form->error($model,'startTime'); ?>
                    <p class="help-block">什么时候出现在作品新增页面</p>
                </div>
                <div class="col-xs-6 col-sm-6">
                    <?php echo $form->labelEx($model,'expiredTime'); ?>
                    <?php 
                        $model->expiredTime=$model->expiredTime ? zmf::time($model->expiredTime,'Y/m/d H:i:s') : '';
                        $this->widget('ext.timepicker.timepicker', array(
                        'model'=>$model,
                        'name'=>'expiredTime',
                        'options'=>array(
                            'showOn'=>'focus',
                            'showAnim'=>'fadeIn',
                            'dateFormat'=>'yy/mm/dd',
                            'timeFormat'=>'hh:mm:ss',    
                        ),
                        ));
                    ?>   
                    <?php echo $form->error($model,'expiredTime'); ?>
                </div>
            </div>            
	</div>
    
        <div class="form-group">            
            <div class="row">
                <div class="col-xs-6 col-sm-6">
                    <?php echo $form->labelEx($model,'voteStart'); ?>
                    <?php 
                        $model->voteStart=$model->voteStart ? zmf::time($model->voteStart,'Y/m/d H:i:s') : '';
                        $this->widget('ext.timepicker.timepicker', array(
                        'model'=>$model,
                        'name'=>'voteStart',
                        'options'=>array(
                            'showOn'=>'focus',
                            'showAnim'=>'fadeIn',
                            'dateFormat'=>'yy/mm/dd',
                            'timeFormat'=>'hh:mm:ss',    
                        ),			    
                        ));
                    ?>               
                    <?php echo $form->error($model,'voteStart'); ?>
                    <p class="help-block">默认与活动开始时间相同</p>
                </div>
                <div class="col-xs-6 col-sm-6">
                    <?php echo $form->labelEx($model,'voteEnd'); ?>
                    <?php 
                        $model->voteEnd=$model->voteEnd ? zmf::time($model->voteEnd,'Y/m/d H:i:s') : '';
                        $this->widget('ext.timepicker.timepicker', array(
                        'model'=>$model,
                        'name'=>'voteEnd',
                        'options'=>array(
                            'showOn'=>'focus',
                            'showAnim'=>'fadeIn',
                            'dateFormat'=>'yy/mm/dd',
                            'timeFormat'=>'hh:mm:ss',    
                        ),			    
                        ));
                    ?>   
                    <?php echo $form->error($model,'voteEnd'); ?>
                    <p class="help-block">默认与活动结束时间相同</p>
                </div>
            </div>            
	</div>
    
        <div class="form-group">
            <?php echo $form->labelEx($model,'desc'); ?>
            <?php echo $form->textArea($model,'desc',array('class'=>'form-control')); ?>
            <p class="help-block">将用于微信分享的描述</p>
            <?php echo $form->error($model,'desc'); ?>
	</div>
    
        <div class="form-group">
            <?php echo $form->labelEx($model,'content'); ?>
            <?php $this->renderPartial('//common/editor_um', array('model' => $model,'content' => $model->content,'uploadurl'=>$uploadurl)); ?>
            <?php echo $form->error($model,'content'); ?>
        </div>

	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/common/uploadify/jquery.uploadify.min.js', CClientScript::POS_END); ?>
<script>
    var imgUploadUrl = "<?php echo Yii::app()->createUrl('/attachments/upload', array('type' => 'activity')); ?>";
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
            type:'activity'
        });
    });
</script>