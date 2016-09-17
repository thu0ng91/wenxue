<?php
/**
 * @filename GoodsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-10 16:12:23 
 */
$uploadurl=Yii::app()->createUrl('/attachments/upload',array('type'=>'goods','imgsize'=>640));
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'goods-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    
    <div class="form-group">
        <?php echo $form->labelEx($model,'title'); ?>        
        <?php echo $form->textField($model,'title',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'classify'); ?>
        <p id="selected-classify"></p>
        <?php echo $form->hiddenField($model,'classify',array('class'=>'form-control')); ?>
        <div class="row">
            <div class="col-xs-6 col-sm-6">
                <?php echo $classifyHtml;?>
            </div>
        </div>
        <p class="help-block">物品分类，必须细分到最后一类</p>
        <?php echo $form->error($model,'classify'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'groupids'); ?>        
        <?php echo $form->dropDownlist($model,'groupids',  Group::listAll(),array('class'=>'form-control','multiple'=>true,'empty'=>'--不限制--')); ?>
        <p class="help-block">限制哪些角色可以看到和购买此物品</p>
        <?php echo $form->error($model,'groupids'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'scorePrice'); ?>        
        <div class="input-group">
            <?php echo $form->textField($model,'scorePrice',array('class'=>'form-control')); ?>
            <span class="input-group-addon">积分</span>
        </div>        
        <?php echo $form->error($model,'scorePrice'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'goldPrice'); ?>  
        <div class="input-group">
            <?php echo $form->textField($model,'goldPrice',array('class'=>'form-control')); ?>
            <span class="input-group-addon">金币</span>
        </div>   
        <?php echo $form->error($model,'goldPrice'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'desc'); ?>        
        <?php echo $form->textArea($model,'desc',array('class'=>'form-control')); ?>
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
<script>
    function selectThisClassify(id,title){
        $('#selected-classify').html('<span class="label label-success">'+title+'</span>');
        $('#Goods_classify').val(id);
    }
</script>
