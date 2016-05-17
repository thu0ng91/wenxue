<?php

/**
 * @filename ShowcaseLinkController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-17 08:49:53 
 */
$this->renderPartial('/posts/_nav');
if (Yii::app()->user->hasFlash('addShowcaseLinkSuccess')) {
    echo '<div class="alert alert-danger">' . Yii::app()->user->getFlash('addShowcaseLinkSuccess') . '</div>';
}
$this->renderPartial('_form', array('model' => $model));
