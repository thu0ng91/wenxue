<?php

/**
 * @filename all.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-9-18  16:21:56 
 */
?>
<style>
    .goods-container{
        margin-left: -10px;
        margin-right: -10px;
    }
    .goods-container .thumbnail{
        width: 210px;
        display: inline-block;
        padding: 0;
        margin: 0 10px 20px;
        border: 1px solid #F2f2f2;
        border-top: none;
        border-radius: 0
    }
    .goods-container .thumbnail:hover{
        border-color:#F40;
    }
    .goods-container .thumbnail img{
        width: 208px;
        height: 156px;
    }
    .goods-container .thumbnail h3{
        margin: 0 0 5px;
        padding: 0;
        font-size: inherit
    }
    .goods-container .thumbnail .price{
        font-size: 16px;
        color: #F40;
    }
    .goods-container .thumbnail .price span+span:before{
        content:"/\00a0";padding:0 5px;color:#ccc
    }
    .goods-container .thumbnail .price .fa{
        font-size: 13px
    }
    .goods-container .thumbnail .desc{
        height: 36px;
        overflow: hidden
    }
</style>
<div class="container">
    <div class="module">
        <div class="module-body goods-container">
            <?php foreach ($posts as $post){?>
            <?php $this->renderPartial('_item',array('data'=>$post));?>
            <?php $this->renderPartial('_item',array('data'=>$post));?>
            <?php $this->renderPartial('_item',array('data'=>$post));?>
            <?php $this->renderPartial('_item',array('data'=>$post));?>
            <?php $this->renderPartial('_item',array('data'=>$post));?>
            <?php }?>
        </div>
    </div>
</div>