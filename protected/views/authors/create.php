<?php
/**
 * @filename AuthorsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 17:20:08 */
if(Yii::app()->user->hasFlash('addAuthorsSuccess')){
    echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('addAuthorsSuccess').'</div>';
}
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>