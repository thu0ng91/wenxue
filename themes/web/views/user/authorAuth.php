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
    <div class="module-body">
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'authors-form',
                'enableAjaxValidation'=>false,
        )); ?>
        <?php echo $form->errorSummary($model); ?>
            <div class="form-group">
                <?php echo $form->labelEx($model,'password'); ?>
                <?php echo $form->passwordField($model,'password',array('size'=>32,'maxlength'=>32,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'password'); ?>
            </div>
            <div class="form-group">
                <?php echo CHtml::submitButton('验证',array('class'=>'btn btn-primary')); ?>
                <span class="pull-right color-grey"><?php echo CHtml::link('忘记密码？',array('user/forgotAuthorPass'));?></span>
            </div>
        <?php $this->endWidget(); ?>
    </div>
</div><!-- form -->