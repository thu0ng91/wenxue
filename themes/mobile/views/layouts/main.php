<?php $this->beginContent('/layouts/common'); ?>
<footer class="ui-footer">
    <div class="ui-row-flex ui-border-t">
        <div class="ui-col <?php echo $this->selectNav=='zazhi' ? 'active' : '';?>" data-href="<?php echo Yii::app()->createUrl('zazhi/index');?>"><i class="fa fa-bookmark"></i>杂志</div>
        <div class="ui-col <?php echo $this->selectNav=='contribution' ? 'active' : '';?>" data-href="<?php echo Yii::app()->createUrl('site/info',array('code'=>'contribution'));?>"><i class="fa fa-tags"></i>投稿</div>
        <div class="ui-col <?php echo $this->selectNav=='about' ? 'active' : '';?>" data-href="<?php echo Yii::app()->createUrl('site/info',array('code'=>'about'));?>"><i class="fa fa-user"></i>关于</div>
    </div>
</footer>
<?php echo $content; ?>
<?php $this->endContent(); ?>