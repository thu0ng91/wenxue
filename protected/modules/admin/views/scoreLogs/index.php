<?php
/**
 * @filename ScoreLogsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:21:16 */
 $this->renderPartial('_nav'); 
 ?>
   <table class="table table-hover table-striped">
    <tr>
        <th>ID</th>      
                <th><?php echo $model->getAttributeLabel("id");?></th>
                <th><?php echo $model->getAttributeLabel("uid");?></th>
                <th><?php echo $model->getAttributeLabel("classify");?></th>
                <th><?php echo $model->getAttributeLabel("logid");?></th>
                <th><?php echo $model->getAttributeLabel("score");?></th>
                <th><?php echo $model->getAttributeLabel("cTime");?></th>
                <th>操作</th>
    </tr>
    
    <?php foreach ($posts as $data): ?> 
    <tr>
        <td><?php echo $data->id; ?></td>
                <td><?php echo $data->id;?></td>
                <td><?php echo $data->uid;?></td>
                <td><?php echo $data->classify;?></td>
                <td><?php echo $data->logid;?></td>
                <td><?php echo $data->score;?></td>
                <td><?php echo $data->cTime;?></td>
                <td>
            <?php echo CHtml::link('编辑',array('update','id'=> $data->id));?>        
            <?php echo CHtml::link('删除',array('delete','id'=> $data->id));?>
        </td>
    </tr>    
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
