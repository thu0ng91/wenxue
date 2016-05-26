<div class="container login-reg-module">
    <div class="main-part">
        <?php $this->renderPartial('/site/login-carousel');?>
    </div>
    <div class="aside-part">
        <div class="module">
            <div class="module-body">
                <?php $form=$this->beginWidget('CActiveForm', array(
                        'id'=>'users-addUser-form',
                        'enableAjaxValidation'=>false,
                )); ?>
                <div class="form-group">
                <?php echo $form->labelEx($model,'truename'); ?>
                <?php echo $form->textField($model,'truename',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'truename'); ?>
                </div>
                <div class="form-group">
                <?php echo $form->labelEx($model,'password'); ?>
                <?php echo $form->passwordField($model,'password',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'password'); ?>
                </div> 
                <div class="form-group">
                <?php echo $form->labelEx($model,'content'); ?>
                <?php echo $form->textArea($model,'content',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'content'); ?>
                </div>        
                <div class="form-group text-center">
                    <div class="btn-group" role="group">
                        <?php echo CHtml::link('<i class="fa fa-angle-double-left"></i> 登录',array('site/login'),array('class'=>'btn btn-default'));?>
                        <?php echo CHtml::submitButton('注册',array('class'=>'btn btn-success')); ?>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>