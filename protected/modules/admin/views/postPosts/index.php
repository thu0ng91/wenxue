<?php
/**
 * @filename PostPostsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-05 04:08:51 */
$this->renderPartial('_nav');
?>
<table class="table table-hover table-striped">
    <tr>  
        <th><?php echo $mode->getAttributeLabel("id"); ?></th>
        <th><?php echo $mode->getAttributeLabel("uid"); ?></th>
        <th><?php echo $mode->getAttributeLabel("aid"); ?></th>
        <th><?php echo $mode->getAttributeLabel("tid"); ?></th>
        <th><?php echo $mode->getAttributeLabel("comments"); ?></th>
        <th><?php echo $mode->getAttributeLabel("favors"); ?></th>
        <th><?php echo $mode->getAttributeLabel("cTime"); ?></th>
        <th><?php echo $mode->getAttributeLabel("updateTime"); ?></th>
        <th><?php echo $mode->getAttributeLabel("open"); ?></th>
        <th><?php echo $mode->getAttributeLabel("status"); ?></th>
        <th>操作</th>
    </tr>

    <?php foreach ($posts as $data): ?> 
        <tr>
            <td><?php echo $data->id; ?></td>
            <td><?php echo $data->uid; ?></td>
            <td><?php echo $data->aid; ?></td>
            <td><?php echo $data->tid; ?></td>
            <td><?php echo $data->comments; ?></td>
            <td><?php echo $data->favors; ?></td>
            <td><?php echo $data->cTime; ?></td>
            <td><?php echo $data->updateTime; ?></td>
            <td><?php echo $data->open; ?></td>
            <td><?php echo $data->status; ?></td>
            <td>
                <?php echo CHtml::link('编辑', array('update', 'id' => $data->id)); ?>        
                <?php echo CHtml::link('删除', array('delete', 'id' => $data->id)); ?>
            </td>
        </tr>    
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
