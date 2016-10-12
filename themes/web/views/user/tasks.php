<?php
foreach ($tasks as $task){
    if($task['type']==1){//一次性任务
        $desc='一天内进行'.$task['num'].'次，奖励'.$task['score'].'积分'.($task['endTime']>0 ? '，'.zmf::time($task['endTime'], 'm/d H:i:s').'结束' : '');
    }else{
        
    }
?>
<div class="media">
    <div class="media-left">
        
    </div>
    <div class="media-body">
        <p><?php echo $task['title'];?><br/><span class="color-grey"><?php echo $desc;?></span></p>
    </div>
    <div class="media-right">
        <p><?php echo $task['receive'] ? CHtml::link('进行中','javascript:;',array('class'=>'btn btn-xs btn-default disabled')) : CHtml::link('领取','javascript:;',array('class'=>'btn btn-xs btn-default','action'=>'ajax','action-data'=>$task['action']));?></p>
    </div>
</div>
<?php }
