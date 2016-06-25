<div class="container login-reg-module">
    <div class="main-part">
        <?php $this->renderPartial('/site/login-carousel');?>
    </div>
    <div class="aside-part">
        <ul class="nav nav-tabs">
            <li role="presentation"><?php echo CHtml::link('登录',array('site/login'));?></li>            
            <li role="presentation" class="active"><?php echo CHtml::link('注册',array('site/reg'));?></li>            
        </ul>
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
                    <?php echo $form->labelEx($model,'email'); ?>
                    <?php echo $form->emailField($model,'email',array('class'=>'form-control','maxLength'=>255)); ?>
                    <?php echo $form->error($model,'email'); ?>
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