<div class="thumbnail" id="img-<?php echo $data['id'];?>">
    <img src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $data['imgUrl'];?>" class="lazy img-responsive"/>
    <?php if($from=='selectImg'){?>
    <div class="fixed-mask">
        <a href="javascript:;" class="btn btn-xs btn-default select-gallery-img" data-original="<?php echo $data['original'];?>">就选这张</a>
    </div>
    <?php }else{?>
    <div class="fixed-mask">
        <a href="javascript:;" class="btn btn-xs btn-default btn-copy" data-clipboard-text="<?php echo $data['original'];?>">复制链接</a>
        <a href="<?php echo $data['original'];?>" class="btn btn-xs btn-default" target="_blank">查看大图</a>
    </div>
    <div class="fixed-badge">
        <?php echo CHtml::link('<i class="fa fa-remove"></i>','javascript:;',array('action'=>'del-content','action-type'=>'img','action-data'=>  $data['id'],'action-confirm'=>1,'action-target'=>'img-'.$data['id'])); ?>
    </div>
    <?php }?>
</div>