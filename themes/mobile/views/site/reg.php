<div class="login-form">
    <div class="module">
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
        <?php echo $form->labelEx($model,'contact'); ?>
        <?php echo $form->textField($model,'contact',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'contact'); ?>
        </div>        
        <div class="form-group">
        <?php echo $form->labelEx($model,'content'); ?>
        <?php echo $form->textArea($model,'content',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'content'); ?>
        </div>        
        <div class="form-group">
        <?php echo CHtml::submitButton('注册',array('class'=>'btn btn-success')); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>