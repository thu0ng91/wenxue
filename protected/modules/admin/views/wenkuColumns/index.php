<?php
/**
 * @filename WenkuColumnsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:16:42 */
 $this->renderPartial('_nav'); 
 ?>
 <table class="table table-hover table-striped">
    <tr>    
        <th style="width: 80px"><?php echo $model->getAttributeLabel("id");?></th>        
        <th ><?php echo $model->getAttributeLabel("title");?></th>
        <th style="width: 160px">操作</th>
    </tr>
    
    <?php foreach ($posts as $data): ?> 
    <tr>
        <td><?php echo $data->id;?></td>
        <td><?php echo $data->title;?></td>
        <td>
            <?php echo CHtml::link('编辑',array('update','id'=> $data->id));?>        
            <?php echo CHtml::link('删除',array('delete','id'=> $data->id));?>
        </td>
    </tr>    
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
