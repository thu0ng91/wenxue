<?php
/**
 * @filename ShowcaseLinkController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-17 08:49:53 */
 
$this->breadcrumbs=array(
	'Showcase Links'=>array('index'),
	$model->title,
);

$this->menu=array(
    array('label'=>'List ShowcaseLink', 'url'=>array('index')),
    array('label'=>'Create ShowcaseLink', 'url'=>array('create')),
    array('label'=>'Update ShowcaseLink', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Delete ShowcaseLink', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage ShowcaseLink', 'url'=>array('admin')),
);
?>
<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
		'id',
		'sid',
		'logid',
		'classify',
		'title',
		'faceimg',
		'content',
		'url',
		'status',
		'cTime',
		'startTime',
		'endTime',
    ),
)); ?>