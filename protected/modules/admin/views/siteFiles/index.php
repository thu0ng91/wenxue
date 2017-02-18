<?php
/**
 * @filename SiteFilesController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-18 11:04:15 */
 $this->renderPartial('_nav'); 
 ?>
<p><?php echo CHtml::link('新增',array('create'),array('class'=>'btn btn-danger'));?> </p>
 <table class="table table-hover table-striped">
    <tr>    
        <th style="width: 80px"><?php echo $model->getAttributeLabel("id");?></th>
        <th ><?php echo $model->getAttributeLabel("title");?></th>
        <th ><?php echo $model->getAttributeLabel("url");?></th>
        <th ><?php echo $model->getAttributeLabel("version");?></th>        
        <th ><?php echo $model->getAttributeLabel("updateTime");?></th>
        <th style="width: 160px">操作</th>
    </tr>
    
    <?php foreach ($posts as $data): ?> 
    <tr>
        <td><?php echo $data->id;?></td>
        <td><?php echo $data->title;?></td>
        <td><?php echo $data->url;?></td>
        <td><?php echo $data->version;?></td>
        <td><?php echo zmf::formatTime($data->updateTime);?></td>
        <td>
            <?php echo CHtml::link('编辑',array('update','id'=> $data->id));?>        
            <?php echo CHtml::link('删除',array('delete','id'=> $data->id));?>
        </td>
    </tr>    
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
