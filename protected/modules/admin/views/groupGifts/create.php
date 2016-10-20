<?php
/**
 * @filename GroupGiftsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-10-20 08:32:04 */
$this->renderPartial('_nav'); 
if(Yii::app()->user->hasFlash('addGroupGiftsSuccess')){
    echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('addGroupGiftsSuccess').'</div>';
}
$this->renderPartial('_form', array('model'=>$model)); ?>