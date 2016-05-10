<?php
/**
 * @filename AuthorsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 17:20:08 */
 
$this->breadcrumbs=array(
	'Authors'=>array('index'),
	$model->id,
);

$this->menu=array(
    array('label'=>'List Authors', 'url'=>array('index')),
    array('label'=>'Create Authors', 'url'=>array('create')),
    array('label'=>'Update Authors', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Delete Authors', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage Authors', 'url'=>array('admin')),
);
?>
<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
		'id',
		'uid',
		'authorName',
		'avatar',
		'password',
		'hashCode',
		'content',
		'favors',
		'posts',
		'hits',
		'score',
		'cTime',
		'status',
    ),
)); ?>