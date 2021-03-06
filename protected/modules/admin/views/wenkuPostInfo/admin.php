<?php
/**
 * @filename WenkuPostInfoController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:16:54 */
$this->renderPartial('_nav'); 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'wenku-post-info-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'uid',
		'pid',
		'classify',
		'content',
		'comments',
		'hits',
		'cTime',
		'status',
		'likes',
		'dislikes',
            array(
                    'class'=>'CButtonColumn',
            ),
	),
)); 