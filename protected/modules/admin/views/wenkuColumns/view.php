<?php
/**
 * @filename WenkuColumnsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:16:42 */
$this->renderPartial('_nav'); 
$this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
		'id',
		'belongid',
		'name',
		'title',
		'second_title',
		'classify',
		'position',
		'url',
		'attachid',
		'order',
		'hits',
		'status',
		'cTime',
		'system',
    ),
)); 