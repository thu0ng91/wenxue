<?php
$this->renderPartial('/zazhi/_nav');
?>
<table class="table table-hover">
    <tr>
        <th>用户名</th>
        <th class="text-right">看/评/赞</th>
        <th style="width: 250px">操作</th>
    </tr>
<?php foreach($posts as $row):?>
<?php $this->renderPartial('_view',array('data'=>$row));?>
 <?php endforeach;?>
</table>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>