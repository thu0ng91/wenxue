<?php
/**
 * @filename TipsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-14 20:42:39 */
if(Yii::app()->user->hasFlash('addTipsSuccess')){
    echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('addTipsSuccess').'</div>';
}
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>