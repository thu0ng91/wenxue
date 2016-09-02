<?php

/**
 * @filename _nav.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  15:38:01 
 */
$c = Yii::app()->getController()->id;
$a = Yii::app()->getController()->getAction()->id;
$this->menu=array(
    '用户列表'=>array(
        'link'=>array('users/index'),
        'active'=>in_array($a,array('index'))
    ),
    '新增用户'=>array(
        'link'=>array('users/create'),
        'active'=>in_array($a,array('create'))
    ),
    '管理员'=>array(
        'link'=>array('users/admins'),
        'active'=>in_array($a,array('admins'))
    ),
    '用户组'=>array(
        'link'=>array('group/index'),
        'active'=>in_array($c,array('group'))
    ),
    '用户组权限分类'=>array(
        'link'=>array('groupPowerTypes/index'),
        'active'=>in_array($c,array('groupPowerTypes'))
    ),
    '用户组权限'=>array(
        'link'=>array('groupPowers/index'),
        'active'=>in_array($c,array('groupPowers'))
    ),
    '用户组任务'=>array(
        'link'=>array('groupTasks/index'),
        'active'=>in_array($c,array('groupTasks'))
    ),
);