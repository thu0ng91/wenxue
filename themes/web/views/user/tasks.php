<div class="module">
    <div class="module-header">任务列表</div>
    <div class="module-body">
        <?php if(!empty($tasks)){foreach ($tasks as $task){?>
        <?php echo $this->renderPartial('_task',array('data'=>$task));?>
        <?php }}else{?>
        <p class="help-block text-center">暂无任务</p>
        <?php } ?>
    </div>
</div>