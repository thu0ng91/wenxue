<style>
    .font-bold{
        font-weight: bold
    }
</style>
<div class="main-part">
    <div class="module">
    <?php if(!empty($posts)){?>
        <?php foreach ($posts as $row): ?> 
        <p class="<?php echo $row['new'] ? 'font-bold' : 'help-block';?>" id="notice-<?php echo $row['id'];?>">
            <?php echo $row['content']; ?>
            <span class="pull-right">
                <?php echo zmf::formatTime($row['cTime']);?>
                <?php echo CHtml::link('<i class="fa fa-remove"></i>','javascript:;',array('action'=>'del-content','action-type'=>'notice','action-data'=>  $row['id'],'action-confirm'=>1,'action-target'=>'notice-'.$row['id']));?>
            </span>
        </p>
        <?php endforeach; ?>    
        <?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
    <?php }?>        
    </div>
</div>

