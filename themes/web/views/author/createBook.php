<?php

/**
 * @filename createBook.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-13  17:26:05 
 */
?>

<div class="author-content-holder create-book-form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'books-form',
	'enableAjaxValidation'=>false,
)); ?>
    <?php echo $form->errorSummary($model); ?>
    <?php echo $form->hiddenField($model,'faceImg',array('class'=>'form-control')); ?>
    <div class="row">
        <div class="col-xs-3">
            <div class="form-group">
                <div class="thumbnail">
                    <img src="<?php echo $model->faceImg;?>" alt="更改封面图片" id="book-faceImg">
                    <div class="caption">
                        <p class="text-center"><a href="javascript:;" class="btn btn-default openGallery" role="button"  data-holder="book-faceImg" data-field="<?php echo CHtml::activeId($model, 'faceImg');?>">选择图片</a></p>
                    </div>
                </div>
                <?php echo $form->error($model,'faceImg'); ?>
            </div>
        </div>
        <div class="col-xs-9">
            <div class="form-group">
                <?php echo $form->labelEx($model,'colid'); ?>
                <?php echo $form->dropDownlist($model,'colid',  Column::allCols(),array('class'=>'form-control','empty'=>'--请选择--')); ?>
                <?php echo $form->error($model,'colid'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'title'); ?>
                <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'title'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'desc'); ?>
                <?php echo $form->textArea($model,'desc',array('rows'=>4, 'cols'=>50,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'desc'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'content'); ?>
                <?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'content'); ?>
            </div>
            <div class="form-group">
                <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
            </div>
        </div>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->