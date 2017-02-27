<?php
/**
 * @filename GroupPowersController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:20:55 
 */
$tids=GroupPowerTypes::listAll();
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'group-powers-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'gid'); ?>        
        <?php echo $form->dropDownlist($model,'gid',  Group::listAll(),array('class'=>'form-control','empty'=>'--请选择--')); ?>
        <?php echo $form->error($model,'gid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'tid'); ?>  
        <?php echo $form->dropDownlist($model,'tid',$tids ,array('class'=>'form-control','empty'=>'--请选择--','size'=>count($tids)+1)); ?>
        <?php echo $form->error($model,'tid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'value'); ?>        
        <?php echo $form->textField($model,'value',array('class'=>'form-control')); ?>
        <p class="help-block">“0”表示不允许，大于“0”表示一天内允许的条数</p>
        <?php echo $form->error($model,'value'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'score'); ?>        
        <?php echo $form->textField($model,'score',array('class'=>'form-control')); ?>
        <p class="help-block">“0”表示不增加积分，大于“0”表示每次增加的积分数</p>
        <?php echo $form->error($model,'score'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'exp'); ?>        
        <?php echo $form->textField($model,'exp',array('class'=>'form-control')); ?>
        <p class="help-block">“0”表示不增加经验，大于“0”表示每次增加的经验值</p>
        <?php echo $form->error($model,'exp'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'display'); ?>        
        <?php echo $form->dropDownlist($model,'display', zmf::yesOrNo('admin'),array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'display'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->