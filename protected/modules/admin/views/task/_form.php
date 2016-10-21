<?php
/**
 * @filename TaskController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:21:28 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'task-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'title'); ?>        
        <?php echo $form->textField($model,'title',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>  
    <div class="form-group">
        <?php echo $form->labelEx($model,'action'); ?>        
        <?php echo $form->dropDownlist($model,'action',  GroupPowerTypes::listKeys(),array('class'=>'form-control','empty'=>'--请选择--')); ?>
        <?php echo $form->error($model,'action'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'faceImg'); ?>     
        <p><img src="<?php echo zmf::getThumbnailUrl($model->faceImg, 'a120', 'faceImg');?>" alt="任务图标" id="user-avatar" style="width: 120px;height: 120px;"></p>
        <?php $this->renderPartial('/common/_singleUpload',array('model'=>$model,'fieldName'=>'faceImg','type'=>'faceImg','fileholder'=>'filedata','targetHolder'=>'user-avatar','imgsize'=>'a120','progress'=>true));?>
        <?php echo $form->hiddenField($model,'faceImg',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'faceImg'); ?>
    </div>    
    <div class="form-group">
        <?php echo $form->labelEx($model,'desc'); ?>        
        <?php echo $form->textArea($model,'desc',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'desc'); ?>
    </div>    
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->