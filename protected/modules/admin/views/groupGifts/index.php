<?php
/**
 * @filename GroupGiftsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-10-20 08:32:04 */
$this->renderPartial('_nav');
?>
<table class="table table-hover table-striped">
    <tr>
        <th><?php echo $model->getAttributeLabel("id"); ?></th>
        <th><?php echo $model->getAttributeLabel("groupid"); ?></th>
        <th><?php echo $model->getAttributeLabel("goodsid"); ?></th>
        <th><?php echo $model->getAttributeLabel("num"); ?></th>
        <th>操作</th>
    </tr>

    <?php foreach ($posts as $data): ?> 
    <tr>
        <td><?php echo $data->id; ?></td>
        <td><?php echo $data->groupInfo->title; ?></td>
        <td><?php echo $data->goodsInfo->title; ?></td>
        <td><?php echo $data->num; ?></td>
        <td>
            <?php echo CHtml::link('编辑', array('update', 'id' => $data->id)); ?>        
            <?php echo CHtml::link('删除', array('delete', 'id' => $data->id)); ?>
        </td>
    </tr>    
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
