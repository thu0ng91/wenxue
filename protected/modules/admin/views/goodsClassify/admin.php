<?php
/**
 * @filename GoodsClassifyController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-10 16:12:35 */
$this->renderPartial('_nav'); 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'goods-classify-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'belongid',
		'title',
		'order',
		'goods',
		'level',
            array(
                    'class'=>'CButtonColumn',
            ),
	),
)); 