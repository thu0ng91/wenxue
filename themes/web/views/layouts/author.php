<?php 
$this->beginContent('/layouts/common'); 
$cols=  Column::allCols();
?>
<style>
    .author-module{
        display:block;
        height:100%;
        padding:0
    }
    .author-module .main-part{
        border-right:1px solid #e6e6e6
    }
    .author-bg-container{
        width: 960px;
        height: 300px;
        background: url(<?php echo zmf::config('baseurl');?>common/temp/011.jpg) no-repeat center;
        position:relative
    }
    .author-header{
        width:100px;
        height:200px;
        position:absolute;
        top:50%;
        left:50%;
        margin-top:-100px;
        margin-left:-50px;
    }
    .author-avatar-fixed{
        width: 100px;
        height: 100px;
        margin: 0 auto;
        padding:4px;
        background:rgba(255,255,255,.3);
        border-radius:200px;
    }
    .author-avatar-fixed img{
        border-radius: 200px;
        width: 100%;
        height: 100%;
    }
    .author-header h1{
        text-aligin:center;
        font-size:24px;
        font-weight:700;
        color:#fff;
        padding-top:0;
        margin-top:5px;
        margin-bottom:0;
        padding-bottom:0
    }
    .author-content{
        margin-top:25px;
        float:left
    }
    .author-side-navbar{
        height:100%;
    }
    .author-side-navbar a{
        display:block;
        width:100%;
        padding:10px 0 10px 10px;
    }
    .author-side-navbar .item+.item {
        border-top: 1px solid #eee;
    }
    .author-content-holder{
        padding:10px 0 10px 15px
    }
    .author-content-holder .media{
        border-bottom:1px solid #e6e6e6;
        margin-bottom:10px;
        padding-bottom:10px;
        margin-top:0
    }
    .author-content-holder .media img{
        width:90px;
        height:127px;
    }
</style>
<div class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li<?php echo $this->selectNav == 'zazhi' ? ' class="active"' : ''; ?>><?php echo CHtml::link('首页', zmf::config('baseurl')); ?></li>
                <?php foreach ($cols as $colid=>$colTitle){?>
                <li<?php echo $this->selectNav == 'zazhi' ? ' class="active"' : ''; ?>><?php echo CHtml::link($colTitle, array('book/index')); ?></li>
                <?php }?>
                <li<?php echo $this->selectNav == 'zazhi' ? ' class="active"' : ''; ?>><?php echo CHtml::link('作者专区', array('author/index')); ?></li>                   
                <li<?php echo $this->selectNav == 'about' ? ' class="active"' : ''; ?>><?php echo CHtml::link('读者专区', array('readers/index')); ?></li>
            </ul>
        </div>
    </div> 
</div>

<div class="container">
    <div class="author-bg-container">
        <div class="author-header">
            <div class="author-avatar-fixed">
                <img src="<?php echo $this->authorInfo['avatar'];?>"/>
            </div>
            <h1><?php echo $this->authorInfo['authorName'];?></h1>
        </div>
    </div>
    <div class="author-content module author-module">
        <div class="main-part ">          
            <?php echo $content; ?>
        </div>
        <div class="aside-part author-side-navbar">
            <a href="#" class="item">作品</a>
            <a href="#" class="item">作品</a>
        </div>
    </div>    
    
</div>
<?php $this->endContent(); ?>