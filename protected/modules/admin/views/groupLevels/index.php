<?php
/**
 * @filename GroupLevelsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-25 22:55:01 */
$this->renderPartial('_nav');
?>
<table class="table table-hover table-striped">
    <tr>
        <th><?php echo $model->getAttributeLabel("id"); ?></th>
        <th><?php echo $model->getAttributeLabel("gid"); ?></th>
        <th><?php echo $model->getAttributeLabel("minExp"); ?></th>
        <th><?php echo $model->getAttributeLabel("maxExp"); ?></th>
        <th><?php echo $model->getAttributeLabel("title"); ?></th>
        <th>操作</th>
    </tr>
    <?php foreach ($posts as $data): ?> 
        <tr>
            <td><?php echo $data->id; ?></td>
            <td><?php echo $data->groupInfo->title; ?></td>
            <td><?php echo $data->minExp; ?></td>
            <td><?php echo $data->maxExp; ?></td>
            <td><?php echo $data->title; ?></td>
            <td>
                <?php echo CHtml::link('编辑', array('update', 'id' => $data->id)); ?>        
                <?php echo CHtml::link('删除', array('delete', 'id' => $data->id)); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
