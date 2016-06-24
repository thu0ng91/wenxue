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
<div class="module">
    <div class="module-header">找回密码</div>
    <div class="module-body" id="send-sms-form">
        <div class="form-group">
            <label>手机号</label>
            <div class="input-group">
                <input type="text" class="form-control" value="<?php echo zmf::hideWord($this->userInfo['phone']);?>" disabled="disabled" id="user-phone"/>
                <span class="input-group-btn">
                    <button class="btn btn-primary sendSms-btn" type="button"  data-target="user-phone" data-type="authorPass">发送验证码</button>
                </span>
            </div><!-- /input-group -->            
        </div>
        <div class="form-group">
            <label>验证码</label>
            <input type="number" class="form-control bitian" placeholder="验证码" id="verifycode">                    
        </div>
        <div class="form-group">
            <label>新密码</label>
            <input type="password" class="form-control bitian" placeholder="新密码" id="password">
            <p class="help-block">密码长度不小于6位</p>
        </div>
        <div class="form-group text-right">
            <button class="btn btn-primary nextStep-btn" type="button" data-type="authorPass" data-target="user-phone">提交</button>                        
        </div>
    </div>
</div>