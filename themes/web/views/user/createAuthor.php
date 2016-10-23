<?php
/**
 * @filename AuthorsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 17:20:08 */
 ?>

<div class="module">
    <div class="module-header">完善基本资料</div>
    <div class="module-body">
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'authors-form',
            'enableAjaxValidation'=>false,
    )); ?>
    <?php echo $form->errorSummary($model); ?>
    <?php echo $form->hiddenField($model,'avatar',array('class'=>'form-control')); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'authorName'); ?>
        <?php echo $form->textField($model,'authorName',array('size'=>16,'maxlength'=>16,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'authorName'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'avatar'); ?>
        <div class="media">
            <div class="media-left">
                <img src="<?php echo $model->avatar;?>" alt="作者头像" id="user-avatar" style="width: 120px;height: 160px">
            </div>
            <div class="media-body">
                <p><a href="javascript:;" class="btn btn-default openGallery" role="button" data-holder="user-avatar" data-field="<?php echo CHtml::activeId($model, 'avatar');?>">选择头像</a></p>
                <p class="help-block">作者的头像</p>
                <?php echo $form->error($model,'avatar'); ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password',array('size'=>32,'maxlength'=>32,'class'=>'form-control')); ?>
        <p class="help-block">区别于账户密码，将用于积分兑换等</p>
        <?php echo $form->error($model,'password'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'content'); ?>
        <?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'content'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
    <?php $this->endWidget(); ?>
    </div>
</div><!-- form -->