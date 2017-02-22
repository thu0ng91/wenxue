<?php
/**
 * @filename WenkuAuthorInfoController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:16:15 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'wenku-author-info-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'author'); ?>
        <?php $this->widget('CAutoComplete',
            array(
               'name'=>'suggest_author',
               'url'=>array('wenkuAuthor/search'),
               'max'=>10, //specifies the max number of items to display
               'minChars'=>1,
               'delay'=>500, //number of milliseconds before lookup occurs
               'matchCase'=>false, //match case when performing a lookup?
               'value'=>$model->authorInfo->title, 
               'htmlOptions'=>array('class'=>'form-control'),
               'methodChain'=>".result(function(event,item){ $('#".CHtml::activeId($model, 'author')."').val(item[1]);})",
               ));
        ?>
        <?php echo $form->hiddenField($model,'author'); ?>
        <?php echo $form->error($model,'author'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'classify'); ?>        
        <?php echo $form->dropDownlist($model,'classify',  WenkuAuthorInfo::exClassify('admin'),array('class'=>'form-control','empty'=>'--请选择--')); ?>
        <?php echo $form->error($model,'classify'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'content'); ?>
        <?php $this->renderPartial('//common/editor_um', array('model' => $model,'content' => $model->content,'hiddenToolbar'=>true)); ?>
        <?php echo $form->error($model,'content'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->