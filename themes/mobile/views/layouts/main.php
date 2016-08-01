<?php 
$this->beginContent('/layouts/common'); 
?>
<?php if($this->showTopbar){?>
<header class="top-header">
    <?php if($this->showLeftBtn){?>
    <div class="header-left">
        <i class="fa fa-chevron-left" onclick="<?php echo $this->returnUrl ? "window.location='$this->returnUrl'" : 'history.back()';?>"></i>
    </div>
    <?php }?>
    <div class="header-center">
        <h1><?php echo $this->mobileTitle;?></h1>
    </div>
    <div class="header-right">
        <?php echo CHtml::link('<i class="fa fa-search"></i>',array('search/do'));?>
        <?php if (!$this->uid) { ?>
        <?php echo CHtml::link('<i class="fa fa-bell-o"></i>',array('site/login'));?>
        <?php } else {?>
        <?php echo CHtml::link('<i class="fa fa-bell-o"></i><span id="top-nav-count" class="top-nav-count">0</span>',array('user/notice'),array('class'=>'top-notices'));?>
        <?php } ?>
    </div>
</header>
<?php }?>
<section class="ui-container">
<?php echo $content; ?>
</section>
<?php $this->renderPartial('/layouts/_nav');?>
<?php $this->endContent(); ?>