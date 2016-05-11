<?php

/**
 * @filename BooksController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 22:25:56 
 */
$this->renderPartial('/posts/_nav');
if (Yii::app()->user->hasFlash('addBooksSuccess')) {
    echo '<div class="alert alert-danger">' . Yii::app()->user->getFlash('addBooksSuccess') . '</div>';
}
$this->renderPartial('_form', array('model' => $model));
