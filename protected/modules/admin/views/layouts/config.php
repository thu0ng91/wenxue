<?php

$c = Yii::app()->getController()->id;
$this->beginContent('/layouts/main');
$items = Config::navbar('admin');
foreach ($items as $k => $t) {
    $this->menu[$t] = array(
        'link' => array('config/index', 'type' => $k),
        'active' => $k == $_GET['type']
    );
}
$this->menu['图片列表'] = array(
    'link' => array('attachments/index'),
    'active' => $c == 'attachments'
);
$this->menu['站点文章'] = array(
    'link' => array('siteInfo/index'),
    'active' => $c == 'siteInfo'
);
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'config-addConfigs-form',
    'action' => Yii::app()->createUrl('admin/config/add'),
    'enableAjaxValidation' => false,
        ));
echo $content;
echo CHtml::submitButton('提交', array('class' => 'btn btn-primary', 'name' => ''));
$this->endWidget();
$this->endContent();
