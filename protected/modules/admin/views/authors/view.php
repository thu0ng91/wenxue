<?php

/**
 * @filename AuthorsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 17:20:08 */
$this->renderPartial('/posts/_nav');
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'uid',
        'authorName',
        'avatar',
        'password',
        'hashCode',
        'content',
        'favors',
        'posts',
        'hits',
        'score',
        'cTime',
        'status',
    ),
));
