<?php
/**
 * @filename OrdersController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-19 10:16:06 */
$this->renderPartial('_nav'); 
if(Yii::app()->user->hasFlash('addOrdersSuccess')){
    echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('addOrdersSuccess').'</div>';
}
$this->renderPartial('_form', array('model'=>$model)); ?>