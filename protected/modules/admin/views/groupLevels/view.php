<?php
/**
 * @filename GroupLevelsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-25 22:55:01 */
$this->renderPartial('_nav'); 
$this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
		'id',
		'gid',
		'minExp',
		'maxExp',
		'title',
		'desc',
		'icon',
    ),
)); 