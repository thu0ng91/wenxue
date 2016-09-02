<?php
/**
 * @filename TaskController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:21:28 */
$this->renderPartial('_nav'); 
$this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
		'id',
		'title',
		'faceImg',
		'desc',
		'action',
		'type',
		'continuous',
		'num',
		'score',
		'startTime',
		'endTime',
		'times',
		'cTime',
    ),
)); 