<?php
/**
 * @filename PostThreadsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-04 22:17:36 */
$this->renderPartial('_nav');
?>
<table class="table table-hover table-striped">
    <tr>
        <th><?php echo $model->getAttributeLabel("id"); ?></th>
        <th><?php echo $model->getAttributeLabel("fid"); ?></th>
        <th><?php echo $model->getAttributeLabel("type"); ?></th>
        <th><?php echo $model->getAttributeLabel("uid"); ?></th>
        <th><?php echo $model->getAttributeLabel("title"); ?></th>        
        <th><?php echo $model->getAttributeLabel("hits"); ?></th>
        <th><?php echo $model->getAttributeLabel("posts"); ?></th>
        <th><?php echo $model->getAttributeLabel("comments"); ?></th>
        <th><?php echo $model->getAttributeLabel("favorites"); ?></th>
        <th><?php echo $model->getAttributeLabel("styleStatus"); ?></th>
        <th><?php echo $model->getAttributeLabel("digest"); ?></th>
        <th><?php echo $model->getAttributeLabel("top"); ?></th>
        <th><?php echo $model->getAttributeLabel("open"); ?></th>
        <th><?php echo $model->getAttributeLabel("display"); ?></th>
        <th><?php echo $model->getAttributeLabel("lastpost"); ?></th>
        <th><?php echo $model->getAttributeLabel("lastposter"); ?></th>
        <th><?php echo $model->getAttributeLabel("cTime"); ?></th>
        <th>操作</th>
    </tr>

    <?php foreach ($posts as $data): ?> 
        <tr>
            <td><?php echo $data->id; ?></td>
            <td><?php echo $data->fid; ?></td>
            <td><?php echo $data->type; ?></td>
            <td><?php echo $data->uid; ?></td>
            <td><?php echo $data->title; ?></td>
            <td><?php echo $data->hits; ?></td>
            <td><?php echo $data->posts; ?></td>
            <td><?php echo $data->comments; ?></td>
            <td><?php echo $data->favorites; ?></td>
            <td><?php echo $data->styleStatus; ?></td>
            <td><?php echo $data->digest; ?></td>
            <td><?php echo $data->top; ?></td>
            <td><?php echo $data->open; ?></td>
            <td><?php echo $data->display; ?></td>
            <td><?php echo $data->lastpost; ?></td>
            <td><?php echo $data->lastposter; ?></td>
            <td><?php echo $data->cTime; ?></td>
            <td>
                <?php echo CHtml::link('编辑', array('update', 'id' => $data->id)); ?>        
                <?php echo CHtml::link('删除', array('delete', 'id' => $data->id)); ?>
            </td>
        </tr>    
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
