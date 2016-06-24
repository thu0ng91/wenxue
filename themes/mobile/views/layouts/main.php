<?php 
$this->beginContent('/layouts/common'); 
?>
<div class="navbar navbar-topbar" role="navigation">
    <div class="header-logo"></div>
    <?php $this->renderPartial('/layouts/_user');?>
</div>
<div class="navbar navbar-main">
    <?php $this->renderPartial('/layouts/_nav');?>
</div>
<?php echo $content; ?>
<?php $this->endContent(); ?>