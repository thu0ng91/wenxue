<?php
/**
 * @filename ForumAdminsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-10-21 09:25:32 */
$this->renderPartial('_nav'); 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'forum-admins-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'fid',
		'uid',
		'num',
		'powers',
            array(
                    'class'=>'CButtonColumn',
            ),
	),
)); 