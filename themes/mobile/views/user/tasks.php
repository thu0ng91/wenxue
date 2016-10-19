<div class="module">
    <div class="module-header">任务列表</div>
    <?php if(!empty($tasks)){?>
    <div class="module-body">
        <ul class="ui-list ui-list-function">
        <?php foreach ($tasks as $task): ?> 
        <?php echo $this->renderPartial('_task',array('data'=>$task));?>
        <?php endforeach; ?>     
        </ul>
        <?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
    </div>
    <?php }else{?>
    <div class="module-body padding-body">
        <p class="help-block text-center">暂无任务</p> 
    </div>
    <?php }?>
</div>