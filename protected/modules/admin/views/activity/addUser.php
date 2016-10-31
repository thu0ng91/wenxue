<?php
$this->breadcrumbs=array(
    '首页'=>array('index/index'),
    '活动列表'=>array('activity/index'),
);
?>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'activity-link-addUser-form',
        'enableAjaxValidation' => false,
    ));
    ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'logid'); ?>
        <?php echo $form->textArea($model, 'logid',array('class'=>'form-control')); ?>
        <?php echo $form->error($model, 'logid'); ?>
        <p class="help-block">用户的ID，以英文“,”隔开</p>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton('保存',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->