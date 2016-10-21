<div class="media">
    <div class="media-left"></div>
    <div class="media-body">
        <p><span class="title"><?php echo $data['title'];?></span><br/><span class="color-grey"><?php echo $data['extraDesc'];?></span></p>
    </div>
    <div class="media-right">
        <p><?php echo $data['receive'] ? CHtml::link('进行中','javascript:;',array('class'=>'btn btn-xs btn-default disabled')) : CHtml::link('领取','javascript:;',array('class'=>'btn btn-xs btn-default','action'=>'ajax','action-data'=>$data['action']));?></p>
    </div>
</div>