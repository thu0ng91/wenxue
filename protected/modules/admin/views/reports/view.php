<?php

/**
 * @filename ReportsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-31 22:11:32 
 */
$this->renderPartial('/posts/_nav');
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'uid',
        'contact',
        'logid',
        'classify',
        'ip',
        'desc',
        'url',
        'status',
        'cTime',        
    ),
));
