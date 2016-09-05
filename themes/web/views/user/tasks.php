<?php foreach ($tasks as $task){?>
<div class="media">
    <div class="media-left">
        
    </div>
    <div class="media-body">
        <p><?php echo $task['title'];?></p>
    </div>
    <div class="media-right">
        <p><?php echo CHtml::link('领取','javascript:;',array('class'=>'btn btn-xs btn-default'));?></p>
    </div>
</div>
<?php } ?>
