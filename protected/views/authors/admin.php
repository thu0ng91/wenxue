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
	'Manage',
);
$this->menu=array(
    array('label'=>'List Authors', 'url'=>array('index')),
    array('label'=>'Create Authors', 'url'=>array('create')),
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $('#authors-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    return false;
});
");
?>
<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?><div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'authors-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
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
            array(
                    'class'=>'CButtonColumn',
            ),
	),
)); ?>
