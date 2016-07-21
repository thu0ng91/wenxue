<?php 
$this->beginContent('/layouts/common'); 
?>
<?php if($showTopbar){?>
<header class="top-header">
    <div class="header-left">
        <i class="fa fa-angle-double-left" onclick="history.back()"></i>
    </div>
    <div class="header-center">
        <h1><?php echo $this->mobileTitle;?></h1>
    </div>
    <div class="header-right">
        <?php echo CHtml::link('<i class="fa fa-search"></i>',array('search/do'));?>
        <?php if (!$this->uid) { ?>
        <?php echo CHtml::link('<i class="fa fa-bell-o"></i>',array('site/login'));?>
        <?php } else {?>
        <?php echo CHtml::link('<i class="fa fa-bell-o"></i>',array('user/notice'));?>
        <?php } ?>
    </div>
</header>
<?php }?>
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