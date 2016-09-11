<?php
/**
 * @filename GoodsContentController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-10 16:12:44 */
$this->renderPartial('_nav'); 
if(Yii::app()->user->hasFlash('addGoodsContentSuccess')){
    echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('addGoodsContentSuccess').'</div>';
}
$this->renderPartial('_form', array('model'=>$model)); ?>