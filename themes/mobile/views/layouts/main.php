<?php 
$this->beginContent('/layouts/common'); 
?>
<?php if($this->showTopbar){?>
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
<?php $this->renderPartial('/layouts/_nav');?>
<?php $this->endContent(); ?>