<?php 
$this->beginContent('/layouts/common'); 
$cols=  Column::allCols();
?>
<div class="navbar navbar-topbar" role="navigation">
    <div class="container">
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><?php echo CHtml::link('欢迎来到初心创文网！', zmf::config('baseurl')); ?></li>
            </ul>
            <?php $this->renderPartial('/layouts/_user');?>
        </div>
    </div> 
</div>
<div class="container">
    <div class="header-logo"></div>
    <div class="header-search">
        <div class="input-group">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">作者 <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                </ul>
            </div><!-- /btn-group -->
            <input type="text" class="form-control" aria-label="Text input with dropdown button">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button">
                    <i class="fa fa-search"></i>
                </button>
            </span>
        </div><!-- /input-group -->
    </div>
</div>

<div class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li<?php echo $this->selectNav == 'zazhi' ? ' class="active"' : ''; ?>><?php echo CHtml::link('首页', zmf::config('baseurl')); ?></li>
                <?php foreach ($cols as $colid=>$colTitle){?>
                <li<?php echo $this->selectNav == 'zazhi' ? ' class="active"' : ''; ?>><?php echo CHtml::link($colTitle, array('showcase/index','cid'=>$colid)); ?></li>
                <?php }?>
                <li<?php echo $this->selectNav == 'zazhi' ? ' class="active"' : ''; ?>><?php echo CHtml::link('作者专区', array('author/index')); ?></li>                   
                <li<?php echo $this->selectNav == 'about' ? ' class="active"' : ''; ?>><?php echo CHtml::link('读者专区', array('readers/index')); ?></li>
            </ul>
        </div>
    </div> 
</div>
<?php echo $content; ?>
<div class="clearfix"></div>
<div class="footer">
    <div class="wrapper">
        <p><?php echo CHtml::link(zmf::config('sitename'), zmf::config('baseurl')); ?><span class="pull-right">Copyright&copy;<?php echo date('Y'); ?></span></p>
    </div>
</div>
<div class="side-fixed back-to-top"><a href="#top" title="返回顶部"><span class="fa fa-angle-up"></span></a></div>
<div class="side-fixed feedback"><a href="javascript:;" title="意见反馈" action="feedback"><span class="fa fa-comment"></span></a></div>
<div class="footer-bg"></div>
<?php $this->endContent(); ?>