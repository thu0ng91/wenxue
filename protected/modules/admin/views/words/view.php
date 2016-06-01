<?php
/**
 * @filename WordsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-06-01 05:28:02 */
 
$this->breadcrumbs=array(
	'Words'=>array('index'),
	$model->id,
);

$this->menu=array(
    array('label'=>'List Words', 'url'=>array('index')),
    array('label'=>'Create Words', 'url'=>array('create')),
    array('label'=>'Update Words', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Delete Words', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage Words', 'url'=>array('admin')),
);
?>
<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
		'id',
		'word',
		'type',
		'len',
		'uid',
    ),
)); ?>