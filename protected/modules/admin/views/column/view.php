<?php
/**
 * @filename ColumnController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 16:32:06 */
 
$this->breadcrumbs=array(
	'Columns'=>array('index'),
	$model->name,
);

$this->menu=array(
    array('label'=>'List Column', 'url'=>array('index')),
    array('label'=>'Create Column', 'url'=>array('create')),
    array('label'=>'Update Column', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Delete Column', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage Column', 'url'=>array('admin')),
);
?>
<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
		'id',
		'belongid',
		'title',
		'name',
		'hot',
		'bold',
		'queue',
		'classify',
		'status',
    ),
)); ?>