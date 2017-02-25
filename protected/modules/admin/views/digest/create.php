<?php
/**
 * @filename DigestController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-25 10:46:40 */
$this->renderPartial('_nav'); 
if(Yii::app()->user->hasFlash('addDigestSuccess')){
    echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('addDigestSuccess').'</div>';
}
$this->renderPartial('_form', array('model'=>$model)); ?>