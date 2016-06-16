<div class="module">
    <div class="module-header">消息通知</div>
    <div class="module-body">
    <?php if(!empty($posts)){?>
        <?php foreach ($posts as $row): ?> 
        <div class="media" id="notice-<?php echo $row['id'];?>">
            <div class="media-left">
                <img src="<?php echo zmf::lazyImg();?>" class="media-object lazy a48" data-original="<?php echo $row['avatar'];?>"/>
            </div>
            <div class="media-body <?php echo $row['new'] ? 'font-bold' : 'help-block';?>">
                <p class="help-block">
                    <?php echo CHtml::link($row['truename'],array('user/index','id'=>$row['authorid']));?>                    
                    <?php echo zmf::formatTime($row['cTime']);?>
                    <span class="pull-right">                
                        <?php echo CHtml::link('删除','javascript:;',array('action'=>'delContent','data-type'=>'notice','data-id'=>  $row['id'],'data-confirm'=>1,'data-target'=>'notice-'.$row['id'],'title'=>'删除此消息'));?>
                    </span>
                </p>
                <p><?php echo $row['content']; ?></p>
            </div>
        </div>
        <?php endforeach; ?>     
        <?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
    <?php }else{?>
        <p class="help-block text-center">暂无消息</p>    
    <?php }?>
    </div>
</div>