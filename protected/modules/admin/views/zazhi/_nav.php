<?php

/**
 * @filename _nav.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-14  13:10:55 
 */
$c = Yii::app()->getController()->id;
$a = Yii::app()->getController()->getAction()->id;
$this->menu=array(
    '杂志列表'=>array(
        'link'=>array('zazhi/index'),
        'active'=>in_array($a,array('index'))
    ),
    '新增杂志'=>array(
        'link'=>array('zazhi/create'),
        'active'=>in_array($a,array('create'))
    ),
);