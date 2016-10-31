<?php
$this->renderPartial('_nav');
?>
<table class="table table-hover">
    <tr>
        <th>标题</th>
        <th style="width: 100px;">状态</th>
        <th style="width: 200px;">操作</th>
    </tr>
    <?php foreach ($posts as $row): ?> 
        <?php $this->renderPartial('_view', array('data' => $row)); ?>
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>