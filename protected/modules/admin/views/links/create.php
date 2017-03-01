<?php
/**
 * @filename LinksController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-12-06 15:32:40 */
$this->renderPartial('_nav'); 
if(Yii::app()->user->hasFlash('addLinksSuccess')){
    echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('addLinksSuccess').'</div>';
}
$this->renderPartial('_form', array('model'=>$model)); ?>