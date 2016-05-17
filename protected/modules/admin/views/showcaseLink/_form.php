<?php
/**
 * @filename ShowcaseLinkController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-17 08:49:53 */
 ?>
<style>
    .ui-datepicker{
        display: none
    }
</style>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'showcase-link-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>    
    <?php if($model->classify=='book'){?>    
    <div class="form-group">
        <?php echo $form->labelEx($model,'logid'); ?>
        <?php echo $form->textField($model,'logid',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
        <p class="help-block">请添加<?php echo Showcases::exClassify($model->classify);?>ID</p>
        <?php echo $form->error($model,'logid'); ?>
    </div>
    <?php }elseif($model->classify=='ad'){?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'faceimg'); ?>
        <?php echo $form->textField($model,'faceimg',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'faceimg'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'content'); ?>
        <?php echo $form->textField($model,'content',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'content'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'url'); ?>
        <?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'url'); ?>
    </div> 
    <?php }?>    
    <div class="form-group">
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
        <p class="help-block">什么时候出现在列表页面，默认为发布即显示</p>
        <?php echo $form->error($model,'startTime'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'endTime'); ?>
        <?php 
            $model->endTime=$model->endTime ? zmf::time($model->endTime,'Y/m/d H:i:s') : '';
            $this->widget('ext.timepicker.timepicker', array(
            'model'=>$model,
            'name'=>'endTime',
            'options'=>array(
                'showOn'=>'focus',
                'showAnim'=>'fadeIn',
                'dateFormat'=>'yy/mm/dd',
                'timeFormat'=>'hh:mm:ss',    
            ),			    
            ));
        ?>
        <p class="help-block">什么时候从列表隐藏，默认为不限制</p>
        <?php echo $form->error($model,'endTime'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->