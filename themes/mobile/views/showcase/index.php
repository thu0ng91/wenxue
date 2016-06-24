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