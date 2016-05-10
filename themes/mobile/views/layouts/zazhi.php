<?php $this->beginContent('/layouts/common'); ?>
<div id="pjax-container">
    <?php echo $content; ?>
</div>
<div class="footer-wrapper">
    <div class="footer-logo"><?php echo zmf::config('sitename');?></div>
    <div class="more-awesome"><span><?php echo CHtml::link('投稿',array('site/info','code'=>'contribution'));?><?php echo CHtml::link('关于',array('site/info','code'=>'about'));?></span></div>
    <p class="text-center"><?php $ba=zmf::config('beian');echo $ba ? $ba :'备案信息';?></p>
    <p class="text-center"><?php echo zmf::powered();?>&copy;<?php echo date('Y');?></p>
</div>
<?php $this->endContent(); ?>