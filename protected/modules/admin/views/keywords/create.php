<?php
/**
 * @filename KeywordsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-12-12 20:01:56 */
$this->renderPartial('_nav'); 
if(Yii::app()->user->hasFlash('addKeywordsSuccess')){
    echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('addKeywordsSuccess').'</div>';
}
$this->renderPartial('_form', array('model'=>$model)); ?>