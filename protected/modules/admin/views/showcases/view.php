<?php
/**
 * @filename ShowcasesController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-17 08:49:07 */
 
$this->breadcrumbs=array(
	'Showcases'=>array('index'),
	$model->title,
);

$this->menu=array(
    array('label'=>'List Showcases', 'url'=>array('index')),
    array('label'=>'Create Showcases', 'url'=>array('create')),
    array('label'=>'Update Showcases', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Delete Showcases', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage Showcases', 'url'=>array('admin')),
);
?>
<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
		'id',
		'uid',
		'title',
		'position',
		'display',
		'num',
		'status',
		'cTime',
    ),
)); ?>