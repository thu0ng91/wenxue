<?php
/**
 * @filename GroupLevelsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-25 22:55:01 */
$this->renderPartial('_nav'); 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'group-levels-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'gid',
		'minExp',
		'maxExp',
		'title',
		'desc',
		'icon',
            array(
                    'class'=>'CButtonColumn',
            ),
	),
)); 