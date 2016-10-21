<li class="ui-border-t" id="task-<?php echo $data['id'];?>">
    <div class="ui-list-info">
        <p class="ui-nowrap"><?php echo $data['title'];?></p>
        <p class="ui-nowrap-multi color-grey"><?php echo $data['extraDesc'];?></p>        
    </div>
    <?php echo $data['receive'] ? CHtml::link('进行中','javascript:;',array('class'=>'ui-btn')) : CHtml::link('领取','javascript:;',array('class'=>'ui-btn ui-btn-primary','action'=>'ajax','action-data'=>$data['action']));?>
</li>