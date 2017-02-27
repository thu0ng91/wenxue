<?php
/**
 * @filename WenkuPostInfoController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:16:54 */
 $this->renderPartial('_nav'); 
 ?>
 <table class="table table-hover table-striped">
    <tr>    
                <th style="width: 80px"><?php echo $model->getAttributeLabel("id");?></th>
                <th ><?php echo $model->getAttributeLabel("uid");?></th>
                <th ><?php echo $model->getAttributeLabel("pid");?></th>
                <th ><?php echo $model->getAttributeLabel("classify");?></th>
                <th ><?php echo $model->getAttributeLabel("content");?></th>
                <th ><?php echo $model->getAttributeLabel("comments");?></th>
                <th ><?php echo $model->getAttributeLabel("hits");?></th>
                <th ><?php echo $model->getAttributeLabel("cTime");?></th>
                <th ><?php echo $model->getAttributeLabel("status");?></th>
                <th ><?php echo $model->getAttributeLabel("likes");?></th>
                <th ><?php echo $model->getAttributeLabel("dislikes");?></th>
                <th style="width: 160px">操作</th>
    </tr>
    
    <?php foreach ($posts as $data): ?> 
    <tr>
                <td><?php echo $data->id;?></td>
                <td><?php echo $data->uid;?></td>
                <td><?php echo $data->pid;?></td>
                <td><?php echo $data->classify;?></td>
                <td><?php echo $data->content;?></td>
                <td><?php echo $data->comments;?></td>
                <td><?php echo $data->hits;?></td>
                <td><?php echo $data->cTime;?></td>
                <td><?php echo $data->status;?></td>
                <td><?php echo $data->likes;?></td>
                <td><?php echo $data->dislikes;?></td>
                <td>
            <?php echo CHtml::link('编辑',array('update','id'=> $data->id));?>        
            <?php echo CHtml::link('删除',array('delete','id'=> $data->id));?>
        </td>
    </tr>    
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
