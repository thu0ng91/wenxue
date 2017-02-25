<?php

/**
 * @filename digestes.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2017-2-25  11:05:05 
 */
?>
<div class="module">
    <h2 class="module-header">初心分享</h2>
    <div class="module-body top-digests">
        <?php foreach ($digestes as $k=>$data){?>        
        <div class="media post-item ui-border-b">
            <?php if($data['faceImg']){?>
            <div class="media-left">
                <a href="<?php echo $data['url'];?>" target="_blank">
                    <img class="media-object lazy w70" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $data['faceImg'];?>" alt="<?php echo $data['title'];?>">
                </a>
            </div>
            <?php }?>
            <div class="media-body">
                <p class="no-wrap"><?php echo CHtml::link($data['title'],$data['url'],array('target'=>'_blank'));?></p>
                <p class="color-grey tips no-wrap"><?php echo zmf::subStr($data['content'],40);?></p>
            </div>
        </div>
        <?php }?>
    </div>
</div>