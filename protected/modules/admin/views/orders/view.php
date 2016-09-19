<?php
/**
 * @filename OrdersController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-19 10:16:06 */
$this->renderPartial('_nav'); 
$this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
		'id',
		'orderId',
		'uid',
		'gid',
		'title',
		'desc',
		'faceUrl',
		'classify',
		'content',
		'scorePrice',
		'goldPrice',
		'num',
		'payAction',
		'orderStatus',
		'status',
		'cTime',
		'paidTime',
		'paidOrderId',
		'paidType',
    ),
)); 