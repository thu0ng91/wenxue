<?php
/**
 * @filename WenkuAuthorController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:15:59 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'wenku-author-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'title'); ?>        
        <?php echo $form->textField($model,'title',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'faceImg'); ?>     
        <p><img src="<?php echo zmf::getThumbnailUrl($model->faceImg, 'a120', 'faceImg');?>" alt="修改头像" id="user-avatar" style="width: 120px;height: 120px;"></p>
        <?php $this->renderPartial('/common/_singleUpload',array('model'=>$model,'fieldName'=>'faceImg','type'=>'faceImg','fileholder'=>'filedata','targetHolder'=>'user-avatar','imgsize'=>'a120','progress'=>true));?>
        <?php echo $form->hiddenField($model,'faceImg',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'faceImg'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'bgImg'); ?>     
        <p><img src="<?php echo zmf::getThumbnailUrl($model->bgImg, 'a120', 'faceImg');?>" alt="修改背景图" id="user-bgImg" style="width: 120px;height: 120px;"></p>
        <?php $this->renderPartial('/common/_singleUpload',array('model'=>$model,'fieldName'=>'bgImg','type'=>'faceImg','fileholder'=>'filedata','targetHolder'=>'user-bgImg','imgsize'=>'a120','progress'=>true));?>
        <?php echo $form->hiddenField($model,'bgImg',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'bgImg'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'dynasty'); ?>
        <?php echo $form->dropDownlist($model,'dynasty',  WenkuColumns::getAll(),array('class'=>'form-control','empty'=>'--请选择--')); ?>
        <?php echo $form->error($model,'dynasty'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'status'); ?>        
        <?php echo $form->dropDownlist($model,'status',  Posts::exStatus('admin'),array('class'=>'form-control','empty'=>'--请选择--')); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->