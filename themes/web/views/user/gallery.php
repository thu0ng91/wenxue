<?php

/**
 * @filename gallery.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-23  16:58:57 
 */
?>
<div class="module">
    <div class="module-header">
        我的相册
        <?php echo CHtml::link('<i class="fa fa-plus"></i> 上传图片',array('user/upload'),array('class'=>'pull-right'));?>
    </div>
    <div class="module-body">
        <div class="row">
        <?php foreach ($posts as $post){?>
            <div class="col-xs-2 col-sm-2">
                <img src="<?php echo $post['imgUrl'];?>" class="img-responsive"/>
            </div>
        <?php }?>
        </div>
    </div>
</div>