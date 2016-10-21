<?php
/**
 * @filename ForumAdminsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-10-21 09:25:32 */
$this->renderPartial('_nav');
?>
<table class="table table-hover table-striped">
    <tr>
        <th style="width: 60px"><?php echo $model->getAttributeLabel("id"); ?></th>
        <th style="width: 80px"><?php echo $model->getAttributeLabel("fid"); ?></th>
        <th style="width: 80px"><?php echo $model->getAttributeLabel("uid"); ?></th>
        <th style="width: 80px"><?php echo $model->getAttributeLabel("num"); ?></th>
        <th><?php echo $model->getAttributeLabel("powers"); ?></th>
        <th style="width: 120px">操作</th>
    </tr>
    <?php foreach ($posts as $data): ?> 
        <tr>
            <td><?php echo $data->id; ?></td>
            <td><?php echo $data->forumInfo->title; ?></td>
            <td><?php echo $data->userInfo->truename; ?></td>
            <td><?php echo $data->num; ?></td>
            <td><?php echo ForumAdmins::displayLabels($data->powers); ?></td>
            <td>
                <?php echo CHtml::link('编辑', array('update', 'id' => $data->id)); ?>        
                <?php echo CHtml::link('删除', array('delete', 'id' => $data->id)); ?>
            </td>
        </tr>    
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
