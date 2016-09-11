<?php
/**
 * @filename GoodsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-10 16:12:23 */
$this->renderPartial('_nav'); 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'goods-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'title',
		'desc',
		'scorePrice',
		'goldPrice',
		'content',
		'classify',
		'comments',
		'hits',
		'score',
            array(
                    'class'=>'CButtonColumn',
            ),
	),
)); 