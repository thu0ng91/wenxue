<?php
/**
 * @filename chapter.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-11  17:22:55 
 */
?>
<style>
    .chapter-container{
        width: 800px;
        margin: 0 auto;
    }
    .chapter{
        padding: 10px 15px;        
    }
    .chapter h1{
        padding: 0;
        margin: 0;
        font-size: 28px;
        font-weight: 700
    }
    .chapter .chapter-content{
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #e6e6e6;
        font-size: 14px;
        line-height: 1.75
    }
    .chapter .chapter-content p{
        margin-bottom: 10px;
        text-indent: 2em;
    }
    .chapter-navbar .breadcrumb{
        margin-bottom: 0;
        background: transparent
    }
    .chapter-min-tips{
        color:#ccc;
        margin-top: 10px;
    }
    .chapter-min-tips a{
        color:#ccc;
    }
    .chapter-min-tips span{
        margin-right: 10px;
    }
    .chapter-fixed-navbar{
        width: 70px;
        height: 200px;
        
        position: absolute;
        top: 34px;
        left: 0
    }
    .chapter-fixed-navbar a{
        width: 100%;
        display: block;
        text-align: center;
        padding: 10px;
        background: #fff;
        margin-bottom: 5px
    }
    .chapter-fixed-navbar .fa{
        display: block;
        font-size: 24px;
    }
</style>
<div class="container">
    <div class="chapter-container">
        <div class="chapter-navbar">
            <ol class="breadcrumb">
                <li><a href="#">初心创文首页</a></li>
                <li><a href="#">一级分类</a></li>
                <li><a href="#">二级分类</a></li>
                <li class="active"><?php echo $bookInfo['title']; ?></li>
            </ol>
        </div>
        <div class="module chapter">
            <h1><?php echo $chapterInfo['title']; ?></h1>
            <p class="chapter-min-tips">
                <span>小说：<?php echo $bookInfo['title']; ?></span>
                <span>作者：<?php echo $chapterInfo['aid']; ?></span>
                <span>字数：<?php echo $chapterInfo['words']; ?></span>
                <span>更新时间：<?php echo zmf::time($chapterInfo['updateTime']); ?></span>         
            </p>
            <div class="chapter-content">
                <?php echo Chapters::text($chapterInfo['content']); ?>
            </div>            
        </div>        
        <div class="module">
            <div class="module-header">点评</div>
            <div class="module-body">

            </div>
        </div>
    </div>
    <div class="chapter-fixed-navbar">
        <?php echo CHtml::link('<i class="fa fa-list"></i> 目录','javascript:;');?>
        <?php echo CHtml::link('<i class="fa fa-heart"></i> 收藏','javascript:;');?>
        <?php echo CHtml::link('<i class="fa fa-star"></i> 点评','javascript:;');?>
        <?php echo CHtml::link('<i class="fa fa-mobile"></i> 手机','javascript:;');?>        
        <?php echo CHtml::link('<i class="fa fa-reply"></i> 返回','javascript:;');?>        
    </div>
</div>