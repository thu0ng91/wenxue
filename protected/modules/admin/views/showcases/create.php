<?php
/**
 * @filename ShowcasesController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-17 08:49:07 
 */
$this->renderPartial('/posts/_nav');
if(Yii::app()->user->hasFlash('addShowcasesSuccess')){
    echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('addShowcasesSuccess').'</div>';
}
$this->renderPartial('_form', array('model'=>$model)); 