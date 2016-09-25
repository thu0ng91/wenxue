<?php
/**
 * @filename GroupLevelsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-25 22:55:01 */
$this->renderPartial('_nav'); 
if(Yii::app()->user->hasFlash('addGroupLevelsSuccess')){
    echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('addGroupLevelsSuccess').'</div>';
}
$this->renderPartial('_form', array('model'=>$model)); ?>