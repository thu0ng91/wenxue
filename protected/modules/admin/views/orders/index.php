<?php
/**
 * @filename OrdersController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-19 10:16:06 */
 $this->renderPartial('_nav'); 
 ?>
   <table class="table table-hover table-striped">
    <tr>
        <th>ID</th>      
                <th><?php echo $mode->getAttributeLabel("id");?></th>
                <th><?php echo $mode->getAttributeLabel("orderId");?></th>
                <th><?php echo $mode->getAttributeLabel("uid");?></th>
                <th><?php echo $mode->getAttributeLabel("gid");?></th>
                <th><?php echo $mode->getAttributeLabel("title");?></th>
                <th><?php echo $mode->getAttributeLabel("desc");?></th>
                <th><?php echo $mode->getAttributeLabel("faceUrl");?></th>
                <th><?php echo $mode->getAttributeLabel("classify");?></th>
                <th><?php echo $mode->getAttributeLabel("content");?></th>
                <th><?php echo $mode->getAttributeLabel("scorePrice");?></th>
                <th><?php echo $mode->getAttributeLabel("goldPrice");?></th>
                <th><?php echo $mode->getAttributeLabel("num");?></th>
                <th><?php echo $mode->getAttributeLabel("payAction");?></th>
                <th><?php echo $mode->getAttributeLabel("orderStatus");?></th>
                <th><?php echo $mode->getAttributeLabel("status");?></th>
                <th><?php echo $mode->getAttributeLabel("cTime");?></th>
                <th><?php echo $mode->getAttributeLabel("paidTime");?></th>
                <th><?php echo $mode->getAttributeLabel("paidOrderId");?></th>
                <th><?php echo $mode->getAttributeLabel("paidType");?></th>
                <th>操作</th>
    </tr>
    
    <?php foreach ($posts as $data): ?> 
    <tr>
        <td><?php echo $data->id; ?></td>
                <td><?php echo $data->id;?></td>
                <td><?php echo $data->orderId;?></td>
                <td><?php echo $data->uid;?></td>
                <td><?php echo $data->gid;?></td>
                <td><?php echo $data->title;?></td>
                <td><?php echo $data->desc;?></td>
                <td><?php echo $data->faceUrl;?></td>
                <td><?php echo $data->classify;?></td>
                <td><?php echo $data->content;?></td>
                <td><?php echo $data->scorePrice;?></td>
                <td><?php echo $data->goldPrice;?></td>
                <td><?php echo $data->num;?></td>
                <td><?php echo $data->payAction;?></td>
                <td><?php echo $data->orderStatus;?></td>
                <td><?php echo $data->status;?></td>
                <td><?php echo $data->cTime;?></td>
                <td><?php echo $data->paidTime;?></td>
                <td><?php echo $data->paidOrderId;?></td>
                <td><?php echo $data->paidType;?></td>
                <td>
            <?php echo CHtml::link('编辑',array('update','id'=> $data->id));?>        
            <?php echo CHtml::link('删除',array('delete','id'=> $data->id));?>
        </td>
    </tr>    
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
