<?php
/**
 * @filename LinksController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-12-06 15:32:40 */
$this->renderPartial('_nav');
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'search-form',
    'htmlOptions' => array(
        'class' => 'search-form'
    ),
    'action' => Yii::app()->createUrl('/zmf/links/index'),
    'enableAjaxValidation' => false,
    'method' => 'GET'
        ));
?>
<div class="input-group zmf-input-group">
    <?php echo CHtml::textField("id", $_GET["id"], array("class" => "form-control", "placeholder" => $model->getAttributeLabel("id"))); ?>     
    <?php echo CHtml::textField("title", $_GET["title"], array("class" => "form-control", "placeholder" => $model->getAttributeLabel("title"))); ?>       
    <?php echo CHtml::textField("position", $_GET["position"], array("class" => "form-control", "placeholder" => $model->getAttributeLabel("position"))); ?>     
<?php echo CHtml::textField("typeid", $_GET["typeid"], array("class" => "form-control", "placeholder" => $model->getAttributeLabel("typeid"))); ?>     
<?php echo CHtml::textField("platform", $_GET["platform"], array("class" => "form-control", "placeholder" => $model->getAttributeLabel("platform"))); ?>     
</div><!-- /input-group -->
<div class="form-group text-right">
    <span class="input-group-btn">
<?php echo CHtml::link('新增', array('create'), array('class' => 'btn btn-danger')); ?>        
        <button class="btn btn-default" type="submit">搜索</button>
    </span>
</div>
<?php $this->endWidget(); ?>

<table class="table table-hover">
    <tr>    
        <th style="width: 80px"><?php echo $model->getAttributeLabel("id"); ?></th>
        <th ><?php echo $model->getAttributeLabel("title"); ?></th>
        <th ><?php echo $model->getAttributeLabel("url"); ?></th>
        <th ><?php echo $model->getAttributeLabel("position"); ?></th>
        <th ><?php echo $model->getAttributeLabel("typeid"); ?></th>
        <th ><?php echo $model->getAttributeLabel("platform"); ?></th>
        <th ><?php echo $model->getAttributeLabel("cTime"); ?></th>
        <th style="width: 160px">操作</th>
    </tr>
    <?php foreach ($posts as $data): ?> 
    <tr>
        <td><?php echo $data->id; ?></td>
        <td><?php echo $data->title; ?></td>
        <td><?php echo CHtml::link(zmf::subStr($data->url),$data->url,array('target'=>'_blank')); ?></td>
        <td><?php echo Links::exPositions($data->position); ?></td>
        <td><?php echo $data->typeid; ?></td>
        <td><?php echo Ads::exPlatform($data->platform); ?></td>
        <td><?php echo zmf::formatTime($data->cTime); ?></td>                 
        <td>
            <?php echo CHtml::link('编辑', array('update', 'id' => $data->id)); ?>        
            <?php echo CHtml::link('删除', array('delete', 'id' => $data->id)); ?>
        </td>
    </tr>    
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
