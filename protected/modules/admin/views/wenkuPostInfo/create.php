<?php
/**
 * @filename WenkuPostInfoController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:16:54 */
$this->renderPartial('_nav'); 
if(Yii::app()->user->hasFlash('addWenkuPostInfoSuccess')){
    echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('addWenkuPostInfoSuccess').'</div>';
}
$this->renderPartial('_form', array('model'=>$model)); ?>