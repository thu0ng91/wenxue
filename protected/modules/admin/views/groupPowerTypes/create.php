<?php

/**
 * @filename GroupPowerTypesController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:20:45 */
$this->renderPartial('_nav');
if (Yii::app()->user->hasFlash('addGroupPowerTypesSuccess')) {
    echo '<div class="alert alert-danger">' . Yii::app()->user->getFlash('addGroupPowerTypesSuccess') . '</div>';
}
$this->renderPartial('_form', array('model' => $model));
