<?php
/**
 * @filename GoodsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-10 16:12:23 */
$this->renderPartial('_nav');
?>
<table class="table table-hover table-striped">
    <tr>
        <th><?php echo $model->getAttributeLabel("id"); ?></th>
        <th><?php echo $model->getAttributeLabel("classify"); ?></th>
        <th><?php echo $model->getAttributeLabel("title"); ?></th>
        <th><?php echo $model->getAttributeLabel("scorePrice"); ?></th>
        <th><?php echo $model->getAttributeLabel("goldPrice"); ?></th>
        <th>操作</th>
    </tr>

    <?php foreach ($posts as $data): ?> 
        <tr>
            <td><?php echo $data->id; ?></td>
            <td><?php echo $data->classifyInfo->title; ?></td>
            <td><?php echo $data->title; ?></td>
            <td><?php echo $data->scorePrice; ?></td>
            <td><?php echo $data->goldPrice; ?></td>
            <td>
                <?php echo CHtml::link('编辑', array('update', 'id' => $data->id)); ?>        
                <?php echo CHtml::link('删除', array('delete', 'id' => $data->id)); ?>
            </td>
        </tr>    
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
