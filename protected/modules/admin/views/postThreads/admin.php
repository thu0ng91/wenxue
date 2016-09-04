<?php
/**
 * @filename PostThreadsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-04 22:17:36 */
$this->renderPartial('_nav'); 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'post-threads-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'fid',
		'type',
		'uid',
		'title',
		'faceImg',
		'hits',
		'posts',
		'comments',
		'favorites',
		'styleStatus',
		'digest',
		'top',
		'open',
		'display',
		'lastpost',
		'lastposter',
		'cTime',
            array(
                    'class'=>'CButtonColumn',
            ),
	),
)); 