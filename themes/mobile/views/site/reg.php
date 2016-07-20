<div class="container login-reg-module">
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
                <?php echo $form->labelEx($model,'phone'); ?>
                <?php echo $form->textField($model,'phone',array('class'=>'form-control','maxLength'=>11)); ?>
                <?php echo $form->error($model,'phone'); ?>
                </div>
                <div class="form-group">
                <?php echo $form->labelEx($model,'password'); ?>
                <?php echo $form->passwordField($model,'password',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'password'); ?>
                </div> 
                        
                <div class="form-group text-center">
                    <div class="btn-group" role="group">                        
                        <?php echo CHtml::submitButton('注册',array('class'=>'btn btn-success')); ?>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>