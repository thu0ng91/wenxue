<?php

/**
 * @filename index.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  12:58:04 
 */
$this->renderPartial('_nav');
?>
<style>
    .images-holder{
        float: left;
    }
    .images-holder .img-item{
        float: left;
        margin-right: 5px;
        margin-bottom: 5px;
        width: 120px;
        height: 90px;
        position: relative
    }
    .images-holder .img-item img{
        width: 120px;
        height: 90px;
    }
    .fixed-mask{
        width: 100%;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
        background: rgba(0,0,0,.8);
        padding: 10px;
        display: none
    }
    .img-item:hover .fixed-mask{
        display: block
    }
</style>
<div class="images-holder">
    <?php foreach($posts as $k=>$img){$_thumb=  zmf::getThumbnailUrl($img['filePath'], 120, $img['classify']);?>
    <div class="img-item">
        <img src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $_thumb;?>" class="img-responsive lazy"/>
        <div class="fixed-mask">
            <p><?php echo CHtml::link('复制链接','javascript:;',array('class'=>'btn btn-default btn-xs btn-block'));?></p>
            <p><?php echo CHtml::link('查看大图',$img['filePath'],array('class'=>'btn btn-default btn-xs btn-block','target'=>'_blank'));?></p>
        </div>
    </div>
    <?php }?>
</div>