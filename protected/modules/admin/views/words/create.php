<?php
/**
 * @filename WordsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-06-01 05:28:02 
 */
$this->renderPartial('/posts/_nav');
if(Yii::app()->user->hasFlash('addWordsSuccess')){
    echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('addWordsSuccess').'</div>';
}
$this->renderPartial('_form', array('model'=>$model));