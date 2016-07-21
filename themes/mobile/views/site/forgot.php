<?php

/**
 * @filename forgot.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-6-6  11:27:40 
 */
?>
<div class="login-reg-module">
    <?php echo CHtml::link('<i class="fa fa-remove"></i>',$this->referer ? $this->referer : 'javascript:history.back()',array('class'=>'fixed-return-url'));?>
    <div class="login-reg-form">
        <h1><?php echo CHtml::link(zmf::config('sitename'),  zmf::config('baseurl'));?></h1>
        <div class="form-group">
            <span class="fixed-label"><i class="fa fa-phone"></i></span>
            <input type="number" class="form-control"  placeholder="请输入手机号" id="user-phone"/>      
        </div>
        <div class="form-group">
            <button class="btn btn-default sendSms-btn" type="button"  data-target="user-phone" data-type="forget">发送验证码</button>
        </div>
        <div class="hidden">
            <div class="form-group">
                <span class="fixed-label"><i class="fa fa-exclamation-circle"></i></span>
                <input type="number" class="form-control bitian" placeholder="请输入验证码" id="verifycode">                    
            </div>
            <div class="form-group">
                <span class="fixed-label"><i class="fa fa-lock"></i></span>
                <input type="password" class="form-control bitian" placeholder="新密码（密码长度不小于6位）" id="password">        
                <p class="help-block"></p>
            </div>
            <div class="form-group">
                <button class="btn btn-success nextStep-btn" type="button" data-type="forget" data-target="user-phone">提交</button>            
            </div>
        </div>
    </div>
</div>