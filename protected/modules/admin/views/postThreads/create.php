<?php
/**
 * @filename PostThreadsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-04 22:17:36 */
$this->renderPartial('_nav'); 
if(Yii::app()->user->hasFlash('addPostThreadsSuccess')){
    echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('addPostThreadsSuccess').'</div>';
}
$this->renderPartial('_form', array('model'=>$model)); ?>