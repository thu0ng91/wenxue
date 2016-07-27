<?php
/**
 * @filename editTip.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-16  10:43:22 
 */
$scoreArr=Tips::exScore('admin');
?>

<div class="container">
    <div class="module">
        <div class="padding-body module-body">
        <?php $form=$this->beginWidget('CActiveForm', array( 
            'id'=>'tips-form', 
            'enableAjaxValidation'=>false, 
        )); ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model,'score'); ?>
            <div class="clearfix"></div>
            <?php foreach ($scoreArr as $val=>$label){?>
            <label class="radio-inline"><input value="<?php echo $val;?>" type="radio" name="<?php echo CHtml::activeName($model, 'score');?>" <?php if($model->score==$val){?>checked="checked"<?php }?>> <?php echo $label;?></label>
            <?php }?>
            <?php echo $form->error($model,'score'); ?>
        </div>
        <div class="form-group"> 
            <?php echo $form->labelEx($model,'content'); ?>
            <?php echo $form->textArea($model,'content',array('class'=>'form-control','rows'=>5)); ?>
            <?php echo $form->error($model,'content'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'status'); ?>
            <div class="row">
                <div class="col-xs-2 col-sm-2">
                    <?php echo $form->dropDownlist($model,'status',  Tips::exStatusForUser('admin'),array('class'=>'form-control')); ?>
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-success')); ?>
        </div>
        <?php $this->endWidget(); ?>
        </div>
    </div>
</div>