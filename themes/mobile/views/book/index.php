<style>
    .books-category .media .media-object{
        width: 78px;
        height: 104px;
    }
</style>
<div class="container">
    <ol class="breadcrumb">
        <li><?php echo CHtml::link(zmf::config('sitename').'首页',  zmf::config('baseurl'));?></li>
        <li><?php echo CHtml::link($colInfo['title'],  array('book/index','colid'=>$colInfo['id']));?></li>
    </ol>
    <div class="main-part">
        <div class="module books-category">
            <div class="module-header"><?php echo $colInfo['title'];?></div>
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
    <div class="aside-part">
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
    </div>
</div>
<?php

