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
  <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'search-form',
        'htmlOptions' => array(
            'class'=>'search-form'
        ),
        'action'=>Yii::app()->createUrl('/zmf/wenkupostinfo/index'),
	'enableAjaxValidation'=>false,
        'method'=>'GET'
)); ?>
<div class="input-group zmf-input-group">
          <?php echo CHtml::textField("id",$_GET["id"],array("class"=>"form-control","placeholder"=>$model->getAttributeLabel("id")));?>     
         <?php echo CHtml::textField("uid",$_GET["uid"],array("class"=>"form-control","placeholder"=>$model->getAttributeLabel("uid")));?>     
         <?php echo CHtml::textField("pid",$_GET["pid"],array("class"=>"form-control","placeholder"=>$model->getAttributeLabel("pid")));?>     
         <?php echo CHtml::textField("classify",$_GET["classify"],array("class"=>"form-control","placeholder"=>$model->getAttributeLabel("classify")));?>     
         <?php echo CHtml::textField("content",$_GET["content"],array("class"=>"form-control","placeholder"=>$model->getAttributeLabel("content")));?>     
         <?php echo CHtml::textField("comments",$_GET["comments"],array("class"=>"form-control","placeholder"=>$model->getAttributeLabel("comments")));?>     
         <?php echo CHtml::textField("hits",$_GET["hits"],array("class"=>"form-control","placeholder"=>$model->getAttributeLabel("hits")));?>     
         <?php echo CHtml::textField("cTime",$_GET["cTime"],array("class"=>"form-control","placeholder"=>$model->getAttributeLabel("cTime")));?>     
         <?php echo CHtml::textField("status",$_GET["status"],array("class"=>"form-control","placeholder"=>$model->getAttributeLabel("status")));?>     
         <?php echo CHtml::textField("likes",$_GET["likes"],array("class"=>"form-control","placeholder"=>$model->getAttributeLabel("likes")));?>     
         <?php echo CHtml::textField("dislikes",$_GET["dislikes"],array("class"=>"form-control","placeholder"=>$model->getAttributeLabel("dislikes")));?>     
    </div><!-- /input-group -->
<div class="form-group text-right">
    <span class="input-group-btn">
        <?php echo CHtml::link('新增',array('create'),array('class'=>'btn btn-danger'));?>        
        <button class="btn btn-default" type="submit">搜索</button>
    </span>
</div>
<?php $this->endWidget(); ?>
 
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
