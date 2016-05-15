<?php
/**
 * @filename TipsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-14 20:42:39 */
 
$this->breadcrumbs=array(
	'Tips'=>array('index'),
	$model->id,
);

$this->menu=array(
    array('label'=>'List Tips', 'url'=>array('index')),
    array('label'=>'Create Tips', 'url'=>array('create')),
    array('label'=>'Update Tips', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Delete Tips', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage Tips', 'url'=>array('admin')),
);
?>
<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
		'id',
		'uid',
		'logid',
		'classify',
		'tocommentid',
		'content',
		'platform',
		'score',
		'status',
		'cTime',
		'ip',
		'ipInfo',
    ),
)); ?>