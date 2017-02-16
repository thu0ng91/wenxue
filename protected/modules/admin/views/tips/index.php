<?php

/**
 * @filename index.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2017-2-16  14:13:48 
 */
?>
<table class="table table-hover table-striped">
    <tr>  
        <th style="width: 80px;"><?php echo $model->getAttributeLabel("id"); ?></th>
        <th style="width: 160px;"><?php echo $model->getAttributeLabel("uid"); ?></th>
        <th><?php echo $model->getAttributeLabel("bid"); ?></th>
        <th style="width: 80px;"><?php echo $model->getAttributeLabel("favors"); ?></th>
        <th style="width: 120px;"><?php echo $model->getAttributeLabel("cTime"); ?></th>
        <th style="width: 80px;">操作</th>
    </tr>

    <?php foreach ($posts as $data): ?> 
    <tr>
        <td><?php echo $data->id; ?></td>
        <td><?php echo CHtml::link($data->userInfo->truename,array('index','uid'=>$data->uid)); ?></td>
        <td><?php echo CHtml::link($data->bookInfo->title,array('index','bid'=>$data->bid)); ?></td>

        <td><?php echo $data->favors; ?></td>
        <td><?php echo zmf::formatTime($data->cTime); ?></td>
        <td>
            <?php echo CHtml::link('编辑', array('update', 'id' => $data->id),array('target'=>'_blank')); ?>        
            <?php echo CHtml::link('删除', array('delete', 'id' => $data->id)); ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>