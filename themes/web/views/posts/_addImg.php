<div class="thumbnail">
    <img src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $data['imgUrl'];?>" class="lazy img-responsive"/>
    <?php if($from=='selectImg'){?>
    <div class="fixed-mask">
        <a href="javascript:;" class="btn btn-xs btn-default select-gallery-img" data-original="<?php echo $data['original'];?>">就选这张</a>
    </div>
    <?php }else{?>
    <div class="fixed-mask">
        <a href="javascript:;" class="btn btn-xs btn-default">复制链接</a>
        <a href="javascript:;" class="btn btn-xs btn-default">查看大图</a>
    </div>
    <div class="fixed-badge">
        <a href="javascript:;"><i class="fa fa-remove"></i></a>
    </div>
    <?php }?>
</div>