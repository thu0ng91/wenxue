<?php 
$this->beginContent('/layouts/common'); 
?>
<style>
    .top-header{
        width: 100%;
        height: 45px;
        background: #93ba5f;
        position: fixed;
        top: 0;
        left: 0;
        line-height: 45px;
        color: #fff;
        z-index: 99
    }
    .top-header .header-left{
        position: absolute;
        top: 0;
        left: 15px
    }
    .top-header .header-center{
        text-align: center
    }
    .top-header .header-right{
        position: absolute;
        top: 0;
        right: 15px
    }
    .top-header .fa{
        font-size: 18px;
    }
    .ui-container{
        margin-top: 45px;
        margin-bottom: 45px;
    }
    .footer{
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 45px;
        background: #fff;
        border-top: 1px solid #f2f2f2;        
        text-align: center;
        z-index: 99;
    }
    .footer .ui-tiled{
        margin-top: 5px;
    }
    .footer .fa{
        display: block
    }
</style>
<header class="top-header">
    <div class="header-left">
        <i class="fa fa-angle-double-left" onclick="history.back()"></i>
    </div>
    <div class="header-center">
        <h1>列表 list</h1>
    </div>
    <div class="header-right">
        <i class="fa fa-search"></i>
        <i class="fa fa-bell-o"></i>
    </div>
</header>
<section class="ui-container">
<?php echo $content; ?>
</section>
<footer class="footer">
    <ul class="ui-tiled">
        <li data-href="<?php echo zmf::config('baseurl');?>"><i class="fa fa-home"></i>首页</li>
        <li data-href="<?php echo Yii::app()->createUrl('posts/index',array('type'=>'author'));?>" class="<?php echo $this->selectNav=='authorForum' ? 'active' : '';?>"><i class="fa fa-coffee"></i>作者</li>
        <li data-href="<?php echo Yii::app()->createUrl('posts/index',array('type'=>'reader'));?>" class="<?php echo $this->selectNav=='readerForum' ? 'active' : '';?>"><i class="fa fa-puzzle-piece"></i>读者</li>
        <?php if (!$this->uid) { ?>
        <li data-href="<?php echo Yii::app()->createUrl('site/login');?>"><i class="fa fa-user"></i>我的</li>
        <?php } else {?>
        <li data-href="<?php echo Yii::app()->createUrl('user/index');?>"><i class="fa fa-user"></i>我的</li>
        <?php } ?>
    </ul>
</footer>
<?php $this->endContent(); ?>