<?php
/**
 * @filename search.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-30  11:53:22 
 */
?>
<style>
    .search-page .module{
        border-top: none;
        box-shadow: none
    }
</style>
<div class="container">
    <div class="search-page">
        <ul class="nav nav-tabs">
            <?php $types=  SiteInfo::searchTypes('admin');foreach($types as $_type=>$_title){?>
            <li role="presentation" <?php if($_type==$this->searchType){?>class="active"<?php }?>><?php echo CHtml::link($_title,array('search/do','type'=>$_type,'keyword'=>$this->searchKeyword));?></li>
            <?php }?>
        </ul>
        <div class="module">
            <div class="module-body">
                <?php if(!empty($posts)){?>
                <?php if($this->searchType=='author'){?>
                <?php foreach ($posts as $post){$this->renderPartial('/author/_item',array('data'=>$post));}?>
                <?php }elseif($this->searchType=='book'){?>
                <?php foreach ($posts as $post){$this->renderPartial('/book/_item',array('data'=>$post));}?>
                <?php }elseif($this->searchType=='chapter'){?>
                <?php foreach ($posts as $post){$this->renderPartial('/book/_chapter',array('data'=>$post));}?>
                <?php }elseif($this->searchType=='user'){?>
                <?php foreach ($posts as $post){$this->renderPartial('/user/_item',array('data'=>$post));}?>
                <?php }?>
                <?php }else{?>
                <p class="color-grey text-center">暂无搜索结果，请换个关键词试试！</p>
                <?php }?>
            </div>
        </div>
    </div>
</div>