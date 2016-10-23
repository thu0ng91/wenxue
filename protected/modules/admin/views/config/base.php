<?php echo CHtml::hiddenField('type',$type);?>
<fieldset>
    <legend>功能性设置</legend>
    <p><label>应用环境：</label>
        <select name="appStatus" id="appStatus">
            <option value="1" <?php if($c['appStatus']=='1'){?>selected="selected"<?php }?>>本地开发</option>
            <option value="2" <?php if($c['appStatus']=='2'){?>selected="selected"<?php }?>>线上测试</option>
            <option value="3" <?php if($c['appStatus']=='3'){?>selected="selected"<?php }?>>线上正式</option>
        </select>
    </p>
    <p><label>开启手机网页版：</label>
        <select name="mobile" id="mobile">
            <option value="0" <?php if($c['mobile']=='0'){?>selected="selected"<?php }?>>关闭</option>
            <option value="1" <?php if($c['mobile']=='1'){?>selected="selected"<?php }?>>开启</option>
        </select>
    </p>
    <p><label>静态文件加速地址：</label><input class="form-control" type="text" name="cssJsStaticUrl" id="cssJsStaticUrl" value="<?php echo $c['cssJsStaticUrl'];?>"/></p>
    <p><label>限制同时未完结作品数：</label><input class="form-control" type="text" name="booksLimitNum" id="booksLimitNum" value="<?php echo $c['booksLimitNum'];?>"/></p>
</fieldset>
<fieldset>
    <legend>微信分享设置</legend>
    <p><label>微信AK：</label><input class="form-control" type="text" name="weixin_app_id" id="weixin_app_id" value="<?php echo $c['weixin_app_id'];?>"/></p>
    <p><label>微信SK：</label><input class="form-control" type="text" name="weixin_app_key" id="weixin_app_key" value="<?php echo $c['weixin_app_key'];?>"/></p>
    <p><label>微信回调地址：</label><input class="form-control" type="text" name="weixin_app_callback" id="weixin_app_callback" value="<?php echo $c['weixin_app_callback'];?>"/></p>
</fieldset>
<fieldset>
    <legend>微博分享设置</legend>
    <p><label>微博网站接入APPKEY：</label><input class="form-control" type="text" name="weixin_app_id" id="weiboAppkey" value="<?php echo $c['weiboAppkey'];?>"/></p>
    <p><label>官方微博ID：</label><input class="form-control" type="text" name="weiboRalateUid" id="weiboRalateUid" value="<?php echo $c['weiboRalateUid'];?>"/></p>
</fieldset>