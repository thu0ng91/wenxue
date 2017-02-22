<?php
/**
 * @filename WenkuPostsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:17:08 */
$this->renderPartial('_nav'); 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'wenku-posts-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'uid',
		'dynasty',
		'colid',
		'author',
		'title',
		'second_title',
		'pinyin',
		'content',
		'hits',
		'order',
		'status',
		'updateTime',
		'cTime',
            array(
                    'class'=>'CButtonColumn',
            ),
	),
)); 