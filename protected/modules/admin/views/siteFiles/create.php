<?php
/**
 * @filename SiteFilesController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-18 11:04:15 */
$this->renderPartial('_nav'); 
if(Yii::app()->user->hasFlash('addSiteFilesSuccess')){
    echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('addSiteFilesSuccess').'</div>';
}
$this->renderPartial('_form', array('model'=>$model)); ?>