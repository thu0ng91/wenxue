<?php

/**
 * @filename detail.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-9-18  17:42:29 
 */
?>
<style>
    .goods-detail-container{
        background: #fff;
    }
    .goods-detail-container .media-left img{
        width: 280px;
        height: 210px;
    }
    .goods-content .content-aside{
        width: 240px;
        float: left
    }
    .goods-content .content-main{
        width: 720px;
        float: right
    }
    .goods-content .content-main img{
        margin: 0 auto
    }
</style>
<div class="container goods-detail-container">
    <div class="media">
        <div class="media-left">
            <img src="<?php echo zmf::lazyImg();?>" class="lazy" data-original="<?php echo $info['faceUrl'];?>"/>
        </div>
        <div class="media-body">
            <h1><?php echo $info['title'];?></h1>
            <p><?php echo $info['desc'];?></p>
            <div class="price-holder">
                <p>积分价格：<?php echo $info['scorePrice'];?></p>
                <p>金币价格：<?php echo $info['goldPrice'];?></p>
            </div>
        </div>
    </div>
    <div class="goods-content">
        <div class="content-aside">
            
        </div>
        <div class="content-main">
            <?php echo $info['content'];?>
        </div>
    </div>
</div>