<?php
/**
 * @filename DigestController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-25 10:46:40 */
$this->renderPartial('_nav'); 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'digest-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'uid',
		'title',
		'url',
		'faceImg',
		'content',
		'cTime',
		'status',
            array(
                    'class'=>'CButtonColumn',
            ),
	),
)); 