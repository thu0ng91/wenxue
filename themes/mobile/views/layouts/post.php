<?php $this->beginContent('/layouts/common'); ?>
<header class="top-header">
    <div class="header-left">
        <i class="fa fa-chevron-left" onclick="history.back()"></i>
    </div>    
</header>
<section class="ui-container">
<?php echo $content; ?>
</section>
<?php $this->endContent(); ?>