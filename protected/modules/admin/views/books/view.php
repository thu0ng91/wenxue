<?php
/**
 * @filename BooksController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 22:25:56 */
 
$this->breadcrumbs=array(
	'Books'=>array('index'),
	$model->title,
);

$this->menu=array(
    array('label'=>'List Books', 'url'=>array('index')),
    array('label'=>'Create Books', 'url'=>array('create')),
    array('label'=>'Update Books', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Delete Books', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage Books', 'url'=>array('admin')),
);
?>
<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
		'id',
		'uid',
		'aid',
		'title',
		'faceImg',
		'desc',
		'content',
		'favorites',
		'hits',
		'chapters',
		'comments',
		'words',
		'vip',
		'bookStatus',
		'status',
    ),
)); ?>