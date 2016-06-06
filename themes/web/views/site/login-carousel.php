<?php

/**
 * @filename login-carousel.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-26  14:57:36 
 */
$showcases=  Showcases::getPagePosts('reg', NUll, true,'c640360');
$posts=$showcases['reg']['post'];
?>
<style>
    .login-reg-module .carousel{
        width: 640px;
        height: 360px;
        background: #fff
    }
    .login-reg-module .carousel .item img{
        width: 640px;
        height: 360px;
    }
    .login-reg-module .aside-part .module{
        height: 320px;
        margin-bottom: 0;
        border-top: none;
        box-shadow: none;
        overflow: hidden;
        border-left: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        border-right: 1px solid #ccc;
    }
    .login-reg-module .checkbox{
        margin-top: 0
    }
    .login-reg-module input[type=checkbox]{
        margin-top: 2px
    }
</style>
<?php if(!empty($posts)){?>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="">
    <ol class="carousel-indicators">
        <?php foreach ($posts as $k=>$val){?>
        <li data-target="#carousel-example-generic" data-slide-to="<?php echo $k;?>" <?php if($k==0){?>class="active"<?php }?>></li>
        <?php }?>
    </ol>
    <div class="carousel-inner" role="listbox">
        <?php foreach ($posts as $k=>$val){?>
        <div class="item<?php if($k==0){?> active<?php }?>">
            <img alt="<?php echo $val['title'];?>" src="<?php echo $val['faceimg'];?>" data-holder-rendered="true">
        </div>
        <?php }?>
    </div>
</div>
<?php }