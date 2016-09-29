<style>
    .books-category .media .media-object{
        width: 78px;
        height: 104px;
    }
    .books-category .module{
        border-top: none;
        box-shadow: none
    }
</style>
<div class="container">
    <ol class="breadcrumb">
        <li><?php echo CHtml::link(zmf::config('sitename').'首页',  zmf::config('baseurl'));?></li>
        <?php if(!empty($colInfo)){?>
        <li><?php echo CHtml::link($colInfo['title'],  array('showcase/index','cid'=>$colInfo['id']));?></li>
        <?php }?>
        <li><?php echo $colInfo['title'].'作品库';?></li>
    </ol>
    <div class="main-part books-category">
        <ul class="nav nav-tabs">
            <li role="presentation" class="active">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <?php echo !empty($colInfo) ? $colInfo['title'] : '分类筛选';?> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <?php foreach ($cols as $colid=>$colTitle){?>
                    <li><?php echo zmf::urls($colTitle,'book/index',array('key'=>'colid','value'=>$colid));?></li>
                    <?php }?>
                </ul>
            </li>
            <li role="presentation" class="pull-right">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <?php echo Books::orderConditions($order);?> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <?php $orders=  Books::orderConditions('admin');foreach ($orders as $orderBy=>$orderTitle){?>
                    <li><?php echo zmf::urls($orderTitle,'book/index',array('key'=>'order','value'=>$orderBy));?></li>
                    <?php }?>
                </ul>
            </li>
        </ul>
        <div class="module">            
            <div class="module-body">
                <?php if(!empty($posts)){?>
                    <?php foreach ($posts as $post){$this->renderPartial('/book/_item',array('data'=>$post,'adminLogin'=>false));}?>
                    <?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
                <?php }else{?>
                <p class="help-block text-center">暂无该分类的作品</p>
                <?php }?>
            </div>
        </div>
    </div>
    
<!--    <div class="aside-part">
        <div class="module">
            <div class="module-header">推荐作者</div>
            <div class="module-body">
                
            </div>
        </div>
        <div class="module">
            <div class="module-header">推荐作品</div>
            <div class="module-body">
                
            </div>
        </div>
    </div>-->
</div>
<?php

