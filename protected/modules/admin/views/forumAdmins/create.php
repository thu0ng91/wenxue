<?php
/**
 * @filename ForumAdminsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-10-21 09:25:32 */
$this->renderPartial('_nav'); 
if(Yii::app()->user->hasFlash('addForumAdminsSuccess')){
    echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('addForumAdminsSuccess').'</div>';
}
$this->renderPartial('_form', array('model'=>$model)); ?>