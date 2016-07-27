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
<style>
    .top-searcher{
        
    }
    
    .top-searcher input{
        height: 30px;
        border: none;
        border-radius: 5px;
        padding-left: 5px;
        width: 100%;
    }
    .top-searcher .btn{
        background: #fff;
        border-radius: 5px;
        color: #93ba5f;
        padding: 6px;
        text-align: center;
        width: 100%;
    }
    .fixed-search-nav{
        position: fixed;
        width: 100%;
        height: 45px;
        max-height: 45px;
        line-height: 35px;
        padding: 5px 0;
        box-sizing:border-box;
        color: #ccc;
        top: 45px;
        left: 0;
        background: #fff;
        box-shadow:0 5px 5px #e4e4e4; 
        display: block;        
    }
    .fixed-search-nav .active{
        color: #93ba5f;
        line-height: 35px
    }
</style>
<header class="top-header top-searcher">
    <div class="header-left">
        <i class="fa fa-chevron-left" onclick="history.back()"></i>
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
<div class="fixed-search-nav">
    <ul class="ui-tiled">
        <?php foreach($types as $_type=>$_title){?>
        <li class="ui-border-r<?php if($_type==$this->searchType){?> active<?php }?>" data-href="<?php echo Yii::app()->createUrl('search/do',array('type'=>$_type,'keyword'=>$this->searchKeyword));?>"><?php echo $_title;?></li>
        <?php }?>
    </ul>
</div>
<?php echo $content; ?>
<?php $this->endContent(); ?>
