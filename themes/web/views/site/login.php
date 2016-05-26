<div class="container login-reg-module">
    <div class="main-part">
        <?php $this->renderPartial('/site/login-carousel');?>
    </div>
    <div class="aside-part">
        <div class="module">
            <div class="module-body">
                <?php if($canLogin){$form=$this->beginWidget('CActiveForm', array(
                    'id'=>'login-form',
                    'enableAjaxValidation'=>false,
                    'enableClientValidation'=>false
                )); ?>                
                <?php CHtml::$afterRequiredLabel = '';?>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'username'); ?>
                    <?php echo $form->textField($model,'username', array('class'=>'form-control','placeholder'=>'邮箱/用户名')); ?> <?php echo $form->error($model,'username'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'password'); ?>
                    <?php echo $form->passwordField($model,'password', array('class'=>'form-control','placeholder'=>'请输入密码')); ?> <?php echo $form->error($model,'password'); ?>
                </div>
                <?php $cookieInfo=zmf::getCookie('checkWithCaptcha');if($cookieInfo=='1'){?>
                <div class="form-group">        
                    <?php echo $form->textField($model,'verifyCode', array('class'=>'form-control verify-code')); ?>
                    <?php echo $form->error($model,'verifyCode'); ?>
                    <?php $this->widget ( 'CCaptcha', array ('showRefreshButton' => true, 'clickableImage' => true, 'buttonType' => 'link', 'buttonLabel' => '换一换', 'imageOptions' => array ('alt' => zmf::t('change_verify'), 'align'=>'absmiddle'  ) ) );?>
                </div>
                <?php }?>
                <div class="checkbox"><label><?php echo $form->checkBox($model, 'rememberMe', array('class' => 'remember')); ?> 记住登录状态</label></div>
                <div class="form-group text-center">
                    <div class="btn-group" role="group">
                        <input type="submit" name="login" class="btn btn-success" value="登录"/>
                        <?php echo CHtml::link('注册 <i class="fa fa-angle-double-right"></i>',array('site/reg'),array('class'=>'btn btn-default'));?>
                    </div>              
                </div>                
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
        </div>
    </div>
</div>