<?php

/**
 * @filename index.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  12:57:20 
 */
$this->renderPartial('/users/_nav');
?>
<table class="table table-hover">
    <tr>
        <th style="width: 60px">ID</th>
        <th>用户名</th>
        <th style="width: 160px">用户组</th>
        <th style="width: 80px">手机号</th>
        <th style="width: 160px">邮箱</th>
        <th style="width: 160px">注册时间</th>
        <th style="width: 60px">操作</th>
    </tr>
<?php foreach($posts as $data):?> 
    <tr>
        <td><?php echo CHtml::encode($data->id);?></td>
	<td><?php echo CHtml::link(CHtml::encode($data->truename),array('update','id'=>$data->id));?></td>
	<td><?php echo CHtml::encode($data->groupInfo->title);?></td>
	<td><?php echo CHtml::encode($data->phone);?></td>
	<td><?php echo CHtml::encode($data->email);?></td>
        <td><?php echo zmf::formatTime($data->cTime);?></td>
	<td>
            <?php echo CHtml::link('编辑',array('update','id'=>$data->id));?>
	</td>
    </tr>
 <?php endforeach;?>
</table>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>