<?php

/**
 * @filename index.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-18  16:16:38 
 */
?>
<style>
    .column-page .module{
        padding: 0;
        float: left;
        margin-bottom: 40px;
    }
    .column-min{
        width: 230px;     
        height:360px;
        overflow: hidden
    }
    .column-medium{
        width: 480px;
        margin:0 10px;
        height:360px;
        border: none;
        overflow: hidden
    }
    .column-medium .carousel .item img{
        width: 480px;
        height: 360px;
    }
    .column-medium .media-object{
        width: 64px;
        height: 85px;
    }
    .column-medium .title{
        
    }
    .column-min .module-body{
        padding-top:5px; 
    }
    .column-min .module-body .item{
        padding: 5px 0 6px;
        border-bottom: 1px solid #f2f2f2
    }
    .column-min .module-body .item:last-child{
        border-bottom: none
    }
    .column-min .module-body .item .dot{
        margin-right: 5px
    }
</style>
<div class="container column-page">
    <?php $this->renderPartial('/showcase/columnModule',array('moduleInfo'=>$posts['column11']));?>
    <div class="module column-medium">
        <?php $this->renderPartial('/showcase/carousel',array('moduleInfo'=>$posts['column12']));?>
    </div>
    <?php $this->renderPartial('/showcase/columnModule',array('moduleInfo'=>$posts['column13']));?>
    <?php $this->renderPartial('/showcase/columnModule',array('moduleInfo'=>$posts['column21']));?>
    <?php $this->renderPartial('/showcase/columnModule',array('moduleInfo'=>$posts['column22'],'class'=>'column-medium'));?>       
    <?php $this->renderPartial('/showcase/columnModule',array('moduleInfo'=>$posts['column23']));?>
    <?php $this->renderPartial('/showcase/columnModule',array('moduleInfo'=>$posts['column31']));?>
    <?php $this->renderPartial('/showcase/columnModule',array('moduleInfo'=>$posts['column32'],'class'=>'column-medium'));?>
    <div class="module column-min">
        <div class="module-header">最新评论</div>
        <div class="module-body">
            
        </div>
    </div>
</div>