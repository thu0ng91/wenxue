<?php
/**
 * @filename GroupGiftsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-10-20 08:32:04 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'group-gifts-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'groupid'); ?>
        <?php echo $form->dropDownlist($model,'groupid',  Group::listAll(),array('class'=>'form-control','empty'=>'--请选择--')); ?>
        <p class="help-block">要赠送给哪个用户组</p>
        <?php echo $form->error($model,'groupid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'goodsid'); ?>        
        <?php echo $form->textField($model,'goodsid',array('class'=>'form-control')); ?>
        <p class="help-block">请填入赠送给用户的道具（商品）的ID</p>
        <?php echo $form->error($model,'goodsid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'num'); ?>        
        <?php echo $form->textField($model,'num',array('class'=>'form-control')); ?>
        <p class="help-block">每人可得的数量</p>
        <?php echo $form->error($model,'num'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->