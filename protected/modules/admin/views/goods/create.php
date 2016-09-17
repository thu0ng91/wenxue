<?php

/**
 * @filename GoodsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-10 16:12:23 */
$this->renderPartial('_nav');
if (Yii::app()->user->hasFlash('addGoodsSuccess')) {
    echo '<div class="alert alert-danger">' . Yii::app()->user->getFlash('addGoodsSuccess') . '</div>';
}
$this->renderPartial('_form', array('model' => $model, 'classifyHtml' => $classifyHtml));
