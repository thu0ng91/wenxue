<?php

/**
 * @filename _confirmDia.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-9-19  16:53:51 
 */
?>
<style>
    .goods-buy-confirm{
        background: #fff;
    }
    .goods-buy-confirm .price{
        font-size: 16px;
        color: #F40;
    }
</style>
<div class="goods-buy-confirm">
    <input type="hidden" id="confirm-buy-data" value="<?php echo $passdata;?>"/>
    <p>你将以【<?php echo $label;?>】兑换：</p>
    <p class="title"><?php echo $info['title'];?></p>
    <p class="price text-right"><?php echo $perPrice.$label;?></p>
    <p class="price text-right">x <?php echo $num;?></p>
    <hr>
    <p class="price text-right">= <?php echo $totalPrice.$label;?></p>
    <div class="form-group">
        <label>身份确认</label>
        <input type="password" name="password" id="user-password" class="form-control" placeholder="请输入账户密码确认身份"/>
    </div>
</div>
<div class="clearfix"></div>