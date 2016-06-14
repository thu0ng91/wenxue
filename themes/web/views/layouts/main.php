<?php 
$this->beginContent('/layouts/common'); 
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
        <?php $types=  SiteInfo::searchTypes('admin');?>
        <form action="<?php echo Yii::app()->createUrl('search/do');?>" method="GET">
            <input type="hidden" name="type" id="search-type" value="<?php if(!$this->searchType){$_types=array_keys($types);$_type=$_types[0];}else{$_type=$this->searchType;}echo $_type;?>"/>
            <div class="input-group">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="searchTypeBtn"><?php echo $types[$_type];?> <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <?php foreach($types as $_type=>$_title){?>
                        <li><a href="javascript:;" onclick="searchType('<?php echo $_type;?>','<?php echo $_title;?>')"><?php echo $_title;?></a></li>
                        <?php }?>
                    </ul>
                </div><!-- /btn-group -->
                <input type="text" class="form-control" placeholder="请输入关键词" id="keyword" name="keyword" value="<?php echo $this->searchKeyword;?>">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit" onclick="topSearchBtn()">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div><!-- /input-group -->
        </form>
    </div>
</div>

<div class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-collapse collapse">
            <?php $this->renderPartial('/layouts/_nav');?>
        </div>
    </div>
</div>
<?php echo $content; ?>
<div class="clearfix"></div>
<div class="footer">
    <div class="wrapper">
        <p class="text-center">本站全部作品（包括小说、书评和帖子）版权为原创作者所有 本网站仅为网友写作提供上传空间储存平台。本站所收录作品、互动话题、书库评论及本站所做之广告均属其个人行为
与本站立场无关。网站页面版权为初心创文所有，任何单位，个人未经授权不得转载、复制、分发，以及用作商业用途。</p>
        <p><?php echo CHtml::link(zmf::config('sitename'), zmf::config('baseurl')); ?><span class="pull-right">Copyright&copy;<?php echo date('Y'); ?></span></p>        
    </div>
</div>
<div class="side-fixed back-to-top"><a href="#top" title="返回顶部"><span class="fa fa-angle-up"></span></a></div>
<div class="side-fixed feedback"><a href="javascript:;" title="意见反馈" action="feedback"><span class="fa fa-comment"></span></a></div>
<div class="footer-bg" id="footer-bg"></div>
<?php $this->endContent(); ?>