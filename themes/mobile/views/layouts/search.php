<?php

/**
 * @filename search.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-7-20  17:12:35 
 */
$this->beginContent('/layouts/common'); 
$types=  SiteInfo::searchTypes('admin');
?>
<header class="top-header top-searcher">
    <div class="header-left">
        <i class="fa fa-chevron-left" onclick="<?php echo $this->returnUrl ? "window.location='$this->returnUrl'" : 'history.back()';?>"></i>
    </div>
    <form action="<?php echo Yii::app()->createUrl('search/do');?>" method="GET">
        <input type="hidden" name="type" id="search-type" value="<?php if(!$this->searchType){$_types=array_keys($types);$_type=$_types[0];}else{$_type=$this->searchType;}echo $_type;?>"/>
        <div class="header-center">
            <input type="text" placeholder="请输入关键词" id="keyword" name="keyword" value="<?php echo $this->searchKeyword;?>">
        </div>
        <div class="header-right">
            <button class="btn" type="submit" onclick="topSearchBtn()">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </form>
</header>
<div class="fixed-extra-nav">
    <ul class="ui-tiled">
        <?php foreach($types as $_type=>$_title){?>
        <li class="ui-border-r<?php if($_type==$this->searchType){?> active<?php }?>" data-href="<?php echo Yii::app()->createUrl('search/do',array('type'=>$_type,'keyword'=>$this->searchKeyword));?>"><?php echo $_title;?></li>
        <?php }?>
    </ul>
</div>
<?php echo $content; ?>
<?php $this->endContent(); ?>
