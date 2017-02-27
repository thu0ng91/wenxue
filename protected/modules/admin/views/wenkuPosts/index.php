<?php
/**
 * @filename WenkuPostsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:17:08 */
$this->renderPartial('_nav');
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'search-form',
    'htmlOptions' => array('class' => 'search-form'),
    'action' => Yii::app()->createUrl('/admin/wenkuPosts/index'),
    'enableAjaxValidation' => false,
    'method' => 'GET'
        ));
?>
<div class="input-group zmf-input-group">
    <?php echo CHtml::dropDownList("dynasty", $_GET["dynasty"], WenkuColumns::getAll(), array("class" => "form-control", "empty" => '--请选择--')); ?>
    <?php
    $this->widget('CAutoComplete', array(
        'name' => 'suggest_author',
        'url' => array('wenkuAuthor/search'),
        'max' => 10,
        'minChars' => 1,
        'delay' => 500,
        'matchCase' => false,
        'value' => $_GET["suggest_author"],
        'htmlOptions' => array('class' => 'form-control','placeholder'=>'作者名'),
        'methodChain' => ".result(function(event,item){ $('#author').val(item[1]);})",
    ));
    ?>
    <?php echo CHtml::hiddenField('author', $_GET["author"]); ?>
    <?php echo CHtml::textField("title", $_GET["title"], array("class" => "form-control", "placeholder" => $model->getAttributeLabel("title"))); ?>     
    <?php echo CHtml::dropDownList("order", $_GET["order"], array('len' => '标题长度'), array("class" => "form-control", "empty" => '--排序方式--')); ?> 
    <?php echo CHtml::dropDownList("status", $_GET["status"], Posts::exStatus('admin'), array("class" => "form-control", "empty" => '--请选择--')); ?>
</div><!-- /input-group -->
<div class="form-group text-right">
    <span class="input-group-btn">     
        <button class="btn btn-default" type="submit">搜索</button>
    </span>
</div>
<?php $this->endWidget(); ?>
<table class="table table-hover table-striped">
    <tr>    
        <th style="width: 80px"><?php echo $model->getAttributeLabel("id"); ?></th>
        <th style="width: 80px"><?php echo $model->getAttributeLabel("dynasty"); ?></th>
        <th style="width: 80px"><?php echo $model->getAttributeLabel("author"); ?></th>
        <th ><?php echo $model->getAttributeLabel("title"); ?></th>
        <th style="width: 80px"><?php echo $model->getAttributeLabel("status"); ?></th>
        <th style="width: 160px">操作</th>
    </tr>    
    <?php foreach ($posts as $data): ?> 
        <tr>
            <td><?php echo CHtml::link($data->id, array('view', 'id' => $data->id), array('target' => '_blank')); ?></td>
            <td><?php echo CHtml::link($data->dynastyInfo->title, array('index', 'dynasty' => $data->dynasty)); ?></td>
            <td><?php echo CHtml::link($data->authorInfo->title, array('index', 'author' => $data->author)); ?></td>
            <td><?php echo $data->title; ?></td>            
            <td class="<?php echo $data->status==Posts::STATUS_PASSED ? '' : 'text-danger';?>"><?php echo Posts::exStatus($data->status); ?></td>            
            <td>
                <?php echo CHtml::link('预览', array('/wenku/post', 'id' => $data->id), array('target' => '_blank')); ?>
                <?php echo CHtml::link('编辑', array('update', 'id' => $data->id), array('target' => '_blank')); ?>        
                <?php echo CHtml::link('删除', array('delete', 'id' => $data->id)); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
