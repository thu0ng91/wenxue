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
        <th><?php echo $model->getAttributeLabel("id"); ?></th>
        <th><?php echo $model->getAttributeLabel("uid"); ?></th>
        <th><?php echo $model->getAttributeLabel("tid"); ?></th>
        <th><?php echo $model->getAttributeLabel("comments"); ?></th>
        <th><?php echo $model->getAttributeLabel("favors"); ?></th>
        <th><?php echo $model->getAttributeLabel("updateTime"); ?></th>
        <th>操作</th>
    </tr>

    <?php foreach ($posts as $data): ?> 
        <tr>
            <td><?php echo $data->id; ?></td>
            <td><?php echo CHtml::link($data->userInfo->truename,array('index','uid'=>$data->uid)); ?></td>
            <td><?php echo $data->threadInfo->title; ?></td>
            <td><?php echo $data->comments; ?></td>
            <td><?php echo $data->favors; ?></td>
            <td><?php echo zmf::formatTime($data->updateTime); ?></td>
            <td>
                <?php echo CHtml::link('编辑', array('update', 'id' => $data->id),array('target'=>'_blank')); ?>        
                <?php echo CHtml::link('删除', array('delete', 'id' => $data->id)); ?>
            </td>
        </tr>    
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
