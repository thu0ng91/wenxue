<?php
/**
 * @filename _nav.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-18 11:04:15 */
$c = Yii::app()->getController()->id;
$a = Yii::app()->getController()->getAction()->id;
$this->menu = array(
    '列表' => array(
        'link' => array('index'),
        'active' => in_array($a, array('index'))
    ),
    '新增' => array(
        'link' => array('create'),
        'active' => in_array($a, array('create'))
    ),
);
$this->breadcrumbs=array(
    '管理中心',
    '站点文件'=>array($c.'/index')
);
if($a=='create'){
    $this->breadcrumbs[]='新增';
}elseif($a=='update'){
    $this->breadcrumbs[]='更新';
}