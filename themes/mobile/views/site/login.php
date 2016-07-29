<div class="login-reg-module">
    <?php echo CHtml::link('<i class="fa fa-remove"></i>',$this->referer ? $this->referer : 'javascript:history.back()',array('class'=>'fixed-return-url'));?>
    <div class="login-reg-form">
        <h1><?php echo CHtml::link(zmf::config('sitename'),  zmf::config('baseurl'));?></h1>
        <?php if($canLogin){$form=$this->beginWidget('CActiveForm', array(
            'id'=>'login-form',
            'enableAjaxValidation'=>false,
            'enableClientValidation'=>false
        )); ?>                
        <?php CHtml::$afterRequiredLabel = '';?>
        <div class="form-group">
            <span class="fixed-label"><i class="fa fa-user"></i></span>
            <?php echo $form->textField($model,'phone', array('class'=>'form-control','placeholder'=>'请输入手机号')); ?>
            <?php echo $form->error($model,'phone'); ?>
        </div>
        <div class="form-group">
            <span class="fixed-label"><i class="fa fa-lock"></i></span>
            <?php echo $form->passwordField($model,'password', array('class'=>'form-control','placeholder'=>'请输入密码')); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>
        <?php $cookieInfo=zmf::getCookie('checkWithCaptcha');if($cookieInfo=='1'){?>
        <div class="form-group verify-code-holder">
            <span class="fixed-label"><i class="fa fa-exclamation-circle"></i></span>
            <?php echo $form->textField($model,'verifyCode', array('class'=>'form-control verify-code','placeholder'=>'请输入验证码')); ?>            
            <?php $this->widget ( 'CCaptcha', array ('showRefreshButton' => true, 'clickableImage' => true, 'buttonType' => 'link', 'buttonLabel' => '换一换', 'imageOptions' => array ('alt' => zmf::t('change_verify'), 'align'=>'absmiddle'  ) ) );?>
            <?php echo $form->error($model,'verifyCode'); ?>
        </div>
        <?php }?>
        
        <div class="form-group">
            <input type="submit" name="login" class="btn btn-success" value="登录"/>
        </div>
        <div class="form-group">
            <?php echo CHtml::link('注册',  array('site/reg'),array('class'=>'btn btn-default'));?>   
        </div>
        <?php echo CHtml::link('忘记密码？',array('site/forgot'),array('class'=>'forgot-pass-link'));?>           
        <?php $this->endWidget();}else{ ?>
            <div class="alert alert-danger">
                <p>操作太频繁，请稍后再试！</p>
                <p>
                    <?php echo CHtml::link('返回首页',  zmf::config('baseurl'),array('class'=>'alert-link'));?>
                    <?php echo CHtml::link('前去注册',  array('site/reg'),array('class'=>'alert-link'));?>                
                </p>
            </div>
        <?php }?>
    </div>
    <div class="footer-bg" id="footer-bg"></div>
</div>