<?php
/**
 * @filename TaskRecordsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:21:49 */
$this->renderPartial('_nav'); 
if(Yii::app()->user->hasFlash('addTaskRecordsSuccess')){
    echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('addTaskRecordsSuccess').'</div>';
}
$this->renderPartial('_form', array('model'=>$model)); ?>