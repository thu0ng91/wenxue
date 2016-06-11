<div class="module">
    <div class="module-header">消息通知</div>
    <div class="module-body">
    <?php if(!empty($posts)){?>
        <?php foreach ($posts as $row): ?> 
        <p class="<?php echo $row['new'] ? 'font-bold' : 'help-block';?>" id="notice-<?php echo $row['id'];?>">
            <?php echo $row['content']; ?>
            <span class="pull-right">
                <?php echo zmf::formatTime($row['cTime']);?>
                <?php echo CHtml::link('<i class="fa fa-remove"></i>','javascript:;',array('action'=>'delContent','data-type'=>'notice','data-id'=>  $row['id'],'data-confirm'=>1,'data-target'=>'notice-'.$row['id'],'title'=>'删除此消息'));?>
            </span>
        </p>
        <?php endforeach; ?>     
        <?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
    <?php }else{?>
        <p class="help-block text-center">暂无消息</p>    
    <?php }?>
    </div>
</div>