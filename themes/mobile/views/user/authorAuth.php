<?php
/**
 * @filename AuthorsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 17:20:08 */
 ?>

<div class="login-reg-module">  
    <?php echo CHtml::link('<i class="fa fa-remove"></i>',$this->referer ? $this->referer : 'javascript:history.back()',array('class'=>'fixed-return-url'));?>
    <div class="login-reg-form">
        <h1><?php echo CHtml::link(zmf::config('sitename'),  zmf::config('baseurl'));?></h1>
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'authors-form',
                'enableAjaxValidation'=>false,
        )); ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="form-group">
            <span class="fixed-label"><i class="fa fa-lock"></i></span>
            <?php echo $form->passwordField($model,'password',array('size'=>32,'maxlength'=>32,'class'=>'form-control','placeholder'=>'请输入登录作者中心的密码')); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>
        <div class="form-group">
            <?php echo CHtml::submitButton('验证',array('class'=>'btn btn-success')); ?>
        </div>
        <?php echo CHtml::link('忘记密码？',array('user/forgotAuthorPass'),array('class'=>'forgot-pass-link'));?>  
        <?php $this->endWidget(); ?>
    </div>
</div><!-- form -->