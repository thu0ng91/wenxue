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
        <th style="width: 60px"><?php echo $model->getAttributeLabel("id"); ?></th>
        <th style="width: 60px"><?php echo $model->getAttributeLabel("fid"); ?></th>
        <th style="width: 80px"><?php echo $model->getAttributeLabel("uid"); ?></th>
        <th><?php echo $model->getAttributeLabel("title"); ?></th>        
        <th style="width: 120px">点 / 回 / 藏</th>
        <th style="width: 120px"><?php echo $model->getAttributeLabel("cTime"); ?></th>
        <th style="width: 80px">操作</th>
    </tr>
    <?php foreach ($posts as $data): ?> 
    <tr>
        <td><?php echo CHtml::link($data->id,array('/posts/view','id'=>$data->id),array('target'=>'_blank')); ?></td>
        <td><?php echo CHtml::link($data->forumInfo->title,array('index','fid'=>$data->fid)); ?></td>
        <td><?php echo CHtml::link(zmf::subStr($data->userInfo->truename,4),array('index','uid'=>$data->uid)); ?></td>
        <td><?php echo $data->title; ?></td>        
        <td><?php echo $data->hits; ?> / <?php echo $data->posts; ?> / <?php echo $data->favorites; ?></td>
        <td><?php echo zmf::formatTime($data->cTime); ?></td>
        <td>
            <?php echo CHtml::link('编辑', array('update', 'id' => $data->id)); ?>        
            <?php echo CHtml::link('删除', array('delete', 'id' => $data->id)); ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
