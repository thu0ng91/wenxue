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
    .chapter{
        padding: 10px 15px;
    }
    .chapter h1{
        padding: 0;
        margin: 0;
        font-size: 24px;
    }
    .chapter .chapter-content{
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #e6e6e6;
        font-size: 14px;
        line-height: 1.75
    }
</style>
<div class="container">
    <div class="chapter-navbar">
        <ol class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li><a href="#">Library</a></li>
            <li class="active">Data</li>
        </ol>
    </div>
    <div class="module chapter">
        <h1><?php echo $chapterInfo['title']; ?></h1>
        <div class="chapter-content">
            <?php echo nl2br($chapterInfo['content']); ?>
        </div>
    </div>
</div>