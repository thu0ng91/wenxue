<?php
/**
 * @filename ChaptersController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-11 10:54:32 */
 
$this->breadcrumbs=array(
	'Chapters'=>array('index'),
	$model->title,
);

$this->menu=array(
    array('label'=>'List Chapters', 'url'=>array('index')),
    array('label'=>'Create Chapters', 'url'=>array('create')),
    array('label'=>'Update Chapters', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Delete Chapters', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage Chapters', 'url'=>array('admin')),
);
?>
<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
		'id',
		'uid',
		'aid',
		'bid',
		'title',
		'content',
		'words',
		'comments',
		'hits',
		'status',
		'vip',
		'cTime',
		'updateTime',
		'postTime',
    ),
)); ?>