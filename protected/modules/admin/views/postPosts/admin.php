<?php
/**
 * @filename PostPostsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-05 04:08:51 */
$this->renderPartial('_nav'); 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'post-posts-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'uid',
		'aid',
		'tid',
		'content',
		'comments',
		'favors',
		'cTime',
		'updateTime',
		'open',
		'status',
            array(
                    'class'=>'CButtonColumn',
            ),
	),
)); 