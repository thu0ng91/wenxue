<?php

/**
 * @filename ChaptersController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-11 10:54:32 
 */
$this->renderPartial('/posts/_nav');
if (Yii::app()->user->hasFlash('addChaptersSuccess')) {
    echo '<div class="alert alert-danger">' . Yii::app()->user->getFlash('addChaptersSuccess') . '</div>';
}
$this->renderPartial('_form', array('model' => $model));