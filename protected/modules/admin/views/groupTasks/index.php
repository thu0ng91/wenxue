<?php
/**
 * @filename GroupTasksController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:21:04 */
$this->renderPartial('_nav');
?>
<table class="table table-hover table-striped">
    <tr>     
        <th><?php echo $model->getAttributeLabel("id"); ?></th>
        <th><?php echo $model->getAttributeLabel("gid"); ?></th>
        <th><?php echo $model->getAttributeLabel("tid"); ?></th>
        <th><?php echo $model->getAttributeLabel("action"); ?></th>
        <th><?php echo $model->getAttributeLabel("type"); ?></th>
        <th><?php echo $model->getAttributeLabel("num"); ?></th>
        <th><?php echo $model->getAttributeLabel("score"); ?></th>
        <th><?php echo $model->getAttributeLabel("startTime"); ?></th>
        <th><?php echo $model->getAttributeLabel("endTime"); ?></th>
        <th><?php echo $model->getAttributeLabel("times"); ?></th>
        <th>操作</th>
    </tr>
    <?php foreach ($posts as $data): ?> 
        <tr>
            <td><?php echo $data->id; ?></td>
            <td><?php echo $data->groupInfo->title; ?></td>
            <td><?php echo $data->taskInfo->title; ?></td>
            <td><?php echo $data->action; ?></td>
            <td><?php echo zmf::yesOrNo($data->type); ?></td>
            <td><?php echo $data->num; ?></td>
            <td><?php echo $data->score; ?></td>
            <td><?php echo $data->startTime; ?></td>
            <td><?php echo $data->endTime; ?></td>
            <td><?php echo $data->times; ?></td>
            <td>
                <?php echo CHtml::link('编辑', array('update', 'id' => $data->id)); ?>        
                <?php echo CHtml::link('删除', array('delete', 'id' => $data->id)); ?>
            </td>
        </tr>    
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
