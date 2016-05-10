<?php
$this->renderPartial('/zazhi/_nav');
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'title',
		'content',
		'faceimg',
		'comments',
		'favorites',
		'top',
		'hits',
		'status',
		'cTime',
		'updateTime',
	),
)); ?>
