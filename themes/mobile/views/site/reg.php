<div class="login-reg-module">    
    <?php echo CHtml::link('<i class="fa fa-remove"></i>',$this->referer ? $this->referer : 'javascript:history.back()',array('class'=>'fixed-return-url'));?>
    <div class="login-reg-form">
        <h1><?php echo CHtml::link(zmf::config('sitename'),  zmf::config('baseurl'));?></h1>
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'users-addUser-form',
                'enableAjaxValidation'=>false,
        )); ?>
        <div class="form-group">
            <span class="fixed-label"><i class="fa fa-user"></i></span>
            <?php echo $form->textField($model,'truename',array('class'=>'form-control','placeholder'=>'请输入用户昵称')); ?>
            <?php echo $form->error($model,'truename'); ?>
        </div>
        <div class="form-group">
            <span class="fixed-label"><i class="fa fa-phone"></i></span>
            <?php echo $form->textField($model,'phone',array('class'=>'form-control','maxLength'=>11,'placeholder'=>'请输入手机号')); ?>
            <?php echo $form->error($model,'phone'); ?>
        </div>
        <div class="form-group">
            <span class="fixed-label"><i class="fa fa-lock"></i></span>
            <?php echo $form->passwordField($model,'password',array('class'=>'form-control','placeholder'=>'请输入账号密码（不短于6位）')); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>
        <div class="form-group">
            <?php echo CHtml::submitButton('注册',array('class'=>'btn btn-success')); ?>            
        </div>
        <?php echo CHtml::link('已有账号？点此登录',array('site/login'),array('class'=>'forgot-pass-link'));?>       
        <?php $this->endWidget(); ?>
    </div>
    <div class="footer-bg" id="footer-bg"></div>
</div>