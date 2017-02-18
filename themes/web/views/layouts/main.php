<?php 
$this->beginContent('/layouts/common'); 
$cols=  Column::allCols();
$forums=  PostForums::listAll();
?>
<div class="navbar navbar-default" role="navigation">
    <div class="container">
        <?php echo CHtml::link(zmf::config('sitename'),  zmf::config('baseurl'),array('class'=>'navbar-brand'));?>
        <div class="navbar-collapse collapse">
            <?php $this->renderPartial('/layouts/_nav');?>
            <?php $this->renderPartial('/layouts/_user');?>
        </div>
    </div>
</div>
<div class="navbar navbar-topbar" role="navigation">
    <div class="container">
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav" id="digest-subnavs">
                <?php foreach ($cols as $colid=>$colTitle){?>
                <li><?php echo zmf::urls($colTitle,'book/index',array('key'=>'colid','value'=>$colid),array('title'=>$colTitle.'小说'));?></li>
                <?php }?>
            </ul>
            <ul class="nav navbar-nav displayNone" id="forumns-subnavs">
                <?php foreach ($forums as $fid=>$fTitle){?>
                <li><?php echo CHtml::link($fTitle,array('posts/index','forum'=>$fid));?></li>
                <?php }?>
            </ul>
            <div class="navbar-form navbar-right">
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
    </div>
</div>
<?php echo $content; ?>
<div class="clearfix"></div>
<?php $this->renderPartial('/common/copyright');?>
<div class="footer-bg" id="footer-bg"></div>
<?php $this->endContent(); ?>